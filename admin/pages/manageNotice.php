<?php
    $page_title = 'Manage Notice ||Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
    <i class='bx bx-menu'></i>
    <span class="text">Manage Notice || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel">
    <div>
        <h3>Manage Notice</h3>
    </div>
    <div class="admin_monitor">
        <div class="manage_admin_part">
            <h2>Manage Notice</h2>
            <div class="table-group">
                <table>
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Notice Title</th>
                            <th>Course</th>
                            <th>Section</th>
                            <th>Notice Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>MID TEST</td>
                            <td>C++</td>
                            <td>B</td>
                            <td>2024-03-20 16:42:14</td>
                            <td style="display:flex">
                                <a href="#" class="action-view">👁️</a>
                                <p> / </p>
                                <a href="#" class="action-delete"> 🗑️</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>