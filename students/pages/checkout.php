<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['student_login_id'])) {
    header('Location: ../../studentlogin.php');
    exit();
}
$user_id=$_SESSION['student_login_id'];

// Query to get the user's first name, last name, phone number, and email
$query = "SELECT * FROM student_db WHERE s_ID = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
// Execute the query
$stmt->execute();
// Get the result
$result = $stmt->get_result();
// Fetch the user's details
$row = $result->fetch_assoc();
$name = $row['s_Name'];
$phone = $row['s_ContactNo'];
$email = $row['s_Email'];

$payable = $_GET['payable'];

/* PHP */
$post_data = array();
$post_data['store_id'] = "esalo66771bf6452aa";
$post_data['store_passwd'] = "esalo66771bf6452aa@ssl";
$post_data['total_amount'] = $payable ?? 2500;
$post_data['currency'] = "BDT";
$post_data['tran_id'] = "SSLCZ_TEST_" . uniqid();
$post_data['success_url'] = BASE_URL . 'students/pages/payment.php?' . http_build_query(['status' => 1, 'user_id' => $user_id]);
$post_data['fail_url'] = BASE_URL . 'students/pages/payment.php?' . http_build_query(['status' => 0, 'user_id' => $user_id]);
$post_data['cancel_url'] = BASE_URL . 'students/pages/payment.php?' . http_build_query(['status' => -1, 'user_id' => $user_id]);

# $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

# EMI INFO
$post_data['emi_option'] = "1";
$post_data['emi_max_inst_option'] = "9";
$post_data['emi_selected_inst'] = "9";

# CUSTOMER INFORMATION
$post_data['cus_name'] = $name;
$post_data['cus_email'] = $email;
$post_data['cus_add1'] = "Dhaka";
$post_data['cus_add2'] = "Dhaka";
$post_data['cus_city'] = "Dhaka";
$post_data['cus_state'] = "Dhaka";
$post_data['cus_postcode'] = "1000";
$post_data['cus_country'] = "Bangladesh";
$post_data['cus_phone'] = $phone;
$post_data['cus_fax'] = $phone;

# SHIPMENT INFORMATION
$post_data['ship_name'] = "Triptrip";
$post_data['ship_add1 '] = "Dhaka";
$post_data['ship_add2'] = "Dhaka";
$post_data['ship_city'] = "Dhaka";
$post_data['ship_state'] = "Dhaka";
$post_data['ship_postcode'] = "1230";
$post_data['ship_country'] = "Bangladesh";

# OPTIONAL PARAMETERS
$post_data['value_a'] = $user_id;
$post_data['value_b'] = "ref002";
$post_data['value_c'] = "ref003";
$post_data['value_d'] = "ref004";

$post_data['product_amount'] = $payable ?? 2500;
$post_data['vat'] = "0";
$post_data['discount_amount'] = "0";
$post_data['convenience_fee'] = "0";

# REQUEST SEND TO SSLCOMMERZ
$direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $direct_api_url);
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1);
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


$content = curl_exec($handle);

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if ($code == 200 && !(curl_errno($handle))) {
    curl_close($handle);
    $sslcommerzResponse = $content;
} else {
    curl_close($handle);
    echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
    exit;
}

# PARSE THE JSON RESPONSE
$sslcz = json_decode($sslcommerzResponse, true);

if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
    # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
    # echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
    // echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
    header("Location: ". $sslcz['GatewayPageURL']);
    exit;
} else {
    echo "JSON Data parsing error!";
}