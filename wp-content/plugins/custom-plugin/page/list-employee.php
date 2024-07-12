<?php
global $wpdb;

$table_name = $wpdb->prefix . 'emp_detail';
$employees = $wpdb->get_results("SELECT * FROM {$table_name}", ARRAY_A); // Specify ARRAY_A to get results as an associative array

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee Lists</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo EMS_PLUGIN_URL ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo EMS_PLUGIN_URL ?>css/dataTables.dataTables.min.css">
</head>


<?php
global $wpdb;
$message = "";

// Delete Block
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['emp_del_id']) && !empty($_POST['emp_del_id'])) {

        $table_name = $wpdb->prefix . 'emp_detail';
        $wpdb->delete($table_name, array(
            "id" => intval($_POST['emp_del_id'])
        )
        );

        $message = "Employee deleted successfully";
    }
}

$employees = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
?>

<body>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>Employee Lists</h2>
                <div class="panel panel-primary">
                    <div class="panel-heading">Employee Lists</div>
                    <div class="panel-body">

                        <?php
                        if (!empty($message)) {
                            ?>
                            <div class="alert alert-success">
                                <?php echo $message; ?>
                            </div>
                            <?php
                        }
                        ?>
                        <table class="table" id="tbl-employee">
                            <thead>
                                <tr>
                                    <th>#Id</th>
                                    <th>#Name</th>
                                    <th>#Email</th>
                                    <th>#Phone No</th>
                                    <th>#Gender</th>
                                    <th>#Designation</th>
                                    <th>#Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (count($employees) > 0) {
                                    foreach ($employees as $employee) {
                                        ?>
                                        <tr>
                                            <td><?php echo $employee['id']; ?></td>
                                            <td><?php echo $employee['name']; ?></td>
                                            <td><?php echo $employee['email']; ?></td>
                                            <td><?php echo $employee['phone']; ?></td>
                                            <td><?php echo ucfirst($employee['gender']); ?></td>
                                            <td><?php echo $employee['desg']; ?></td>
                                            <td>
                                                <a href="admin.php?page=employee-system&action=view&empId=<?php echo $employee['id']; ?>"
                                                    class="btn btn-info">View</a>
                                                <a href="admin.php?page=employee-system&action=edit&empId=<?php echo $employee['id']; ?>"
                                                    class="btn btn-warning">Edit</a>

                                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>?page=employee-lists"
                                                    id="frm-delete-emp-<?php echo $employee['id']; ?>" method="post">

                                                    <input type="hidden" name="emp_del_id"
                                                        value="<?php echo $employee['id']; ?>">
                                                </form>
                                                <a href="javascript:void(0)"
                                                    onclick="if(confirm('Are you want to delete')){jQuery('#frm-delete-emp-<?php echo $employee['id']; ?>').submit();}"
                                                    class="btn btn-danger">Delete</a>

                                            </td>
                                        </tr>

                                        <?php
                                    }

                                } else {
                                    echo "No employees";
                                }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="<?php echo EMS_PLUGIN_URL ?>js/jquery.min.js"></script>
    <script src="<?php echo EMS_PLUGIN_URL ?>js/bootstrap.min.js"></script>
    <script src="<?php echo EMS_PLUGIN_URL ?>js/dataTables.min.js"></script>

    <script>
        jQuery(document).ready(function () {
            $('#tbl-employee').DataTable({
                // Your options here
                paging: true, // Example option to disable pagination
            });
        });

    </script>
</body>

</html>                                                                                                                                                                                                                                 