<div class="notice_part">
  <h2>Notice</h2>
  <hr>
  <hr>
  <hr>
  <hr>

  <!-- Class Notice Section -->
  <div class="class_notice">
    <h3><u>Class Notice</u></h3>
    <div class="class_notice_body">
      <div class="class_notice_content">

        <?php
        $student_id = $_SESSION['student_login_id'];

        // Query to get notices for the student's enrolled courses without grades
        $notice_query = mysqli_query($conn, "
            SELECT n.*, s.section, c.c_title 
            FROM notices n 
            JOIN sections s ON n.section_id = s.id 
            JOIN course_db c ON s.course_id = c.c_ID
            WHERE s.id IN (
                SELECT section_id FROM grades WHERE student_id = $student_id
            ) 
            AND s.id NOT IN (
                SELECT section_id FROM grades WHERE student_id = $student_id AND grade IS NOT NULL
            )
        ");

        if (mysqli_num_rows($notice_query) > 0) {
          while ($row = mysqli_fetch_assoc($notice_query)) {
        ?>
            <div class="class_notice_item">
              <p><?php echo $row['created_at']; ?></p>
              <p>Notice Title: <?php echo $row['title']; ?></p>
              <p>Course: <?php echo $row['c_title']; ?></p> <!-- Displaying the course name -->
              <p>Section: <?php echo $row['section']; ?></p> <!-- Displaying the section name -->
              <p class="class_msg"><?php echo $row['message']; ?></p>
            </div>
        <?php
          }
        } else {
          echo '<p>No notices available for your enrolled courses.</p>';
        }
        ?>

      </div>
    </div>
  </div>

  <!-- Public Notice Section -->
  <div class="public_notice">
    <h3><u>Public Notice</u></h3>
    <div class="public_notice_body">
      <div class="public_notice_content">

        <!-- Loop through each public notice -->
        <?php
        $pn_query = mysqli_query($conn, "SELECT * FROM `public_notice_db`");
        while ($row = mysqli_fetch_assoc($pn_query)) {
        ?>
          <div class="public_notice_item">
            <p><?php echo $row['pn_date']; ?></p>
            <p><?php echo $row['pn_title']; ?></p>
            <p class="public_msg"><?php echo $row['pn_message']; ?></p>
          </div>
        <?php
        }
        ?>

      </div>
    </div>
  </div>
</div>