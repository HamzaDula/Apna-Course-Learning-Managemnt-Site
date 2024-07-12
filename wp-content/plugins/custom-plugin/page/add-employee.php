<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Employee</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo EMS_PLUGIN_URL ?>css/bootstrap.min.css">
</head>

<body>


<?php
$message = '';
$status = '';
$action = '';
$empId = '';

// Check if action and empId are set
if (isset($_GET['action']) && isset($_GET['empId'])) {
    global $wpdb;
    $empId = $_GET['empId'];

    // Set the action
    if ($_GET['action'] == 'edit') {
        $action = 'edit';
    } elseif ($_GET['action'] == 'view') {
        $action = 'view';
    }

    // Get employee information
    $table_name = $wpdb->prefix . 'emp_detail';
    $employee = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id=%d", $empId), ARRAY_A);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_submit'])) {
    global $wpdb;

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $gender = sanitize_text_field($_POST['gender']);
    $designation = sanitize_text_field($_POST['desg']);

    if ($action == 'edit') {
        // Update employee data
        $result = $wpdb->update(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'desg' => $designation,
            ),
            array('id' => $empId)
        );

        if ($result !== false) {
            $message = "Data updated successfully";
            $status = 1;
        } else {
            $message = "Failed to update data";
            $status = 0;
        }
    } else {
        // Insert new employee data
        $table_name = $wpdb->prefix . 'emp_detail';
        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'desg' => $designation,
            )
        );

        if ($result) {
            $message = "Data added successfully";
            $status = 1;
        } else {
            $message = "Failed to save data";
            $status = 0;
        }
    }
}
?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>
                <?php
                        if ($action == 'view') {
                            echo "View Employee";
                        } elseif($action=='edit'){
                            echo "Edit Employee";
                        }else
                        {
                            echo "Add Employee";
                        }
                        ?>
                </h2>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <?php
                        if ($action == 'view') {
                            echo "View Employee";
                        } elseif($action=='edit'){
                            echo "Edit Employee";
                        }else
                        {
                            echo "Add Employee";
                        }
                        ?>
                    </div>
                    <div class="panel-body">
                        <?php
                        if (!empty($message)) {
                            if ($status == 1) {
                                ?>
                                <div class="alert alert-success">
                                    <?php echo $message; ?>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-danger">
                                    <?php echo $message; ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
 
                            <form action="<?php if($action == 'edit'){
                        echo "admin.php?page=employee-system&action=edit&empId=".$empId;
                    }else{
                        echo "admin.php?page=employee-system";
                    }?>" method="post" id="add-employee">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" value="<?php if ($action == 'view'|| $action == 'edit') {
                                    echo $employee['name'];
                                } ?>" <?php if ($action == 'view') {
                                     echo "readonly='readonly'";
                                 } ?>class="form-control"
                                    id="name" placeholder="Enter name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" 
                                value="<?php if ($action == 'view' || $action == 'edit') {
                                    echo $employee['email'];
                                } ?>"
                                 <?php if ($action == 'view') {
                                     echo "readonly='readonly'";
                                 } ?>
                                 class="form-control"
                                    id="email" placeholder="Enter email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone No:</label>
                                <input type="text" value="<?php if ($action == 'view' || $action == 'edit') {
                                    echo $employee['phone'];
                                } ?>" <?php if ($action == 'view') {
                                     echo "readonly='readonly'";
                                 } ?> class="form-control" id="phone" placeholder="Enter phone number" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <select <?php if ($action == 'view') {
                                    echo "disabled";
                                } elseif( $action == 'edit'){
                                    
                                }?> name="gender" id="gender"
                                    class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php if (($action == 'view' || $action == 'edit') && $employee['gender'] == 'male') {
                                        echo "selected";
                                    } ?>>Male</option>
                                    <option value="female" <?php if (($action == 'view' || $action == 'edit') && $employee['gender'] == 'female') {
                                        echo "selected";
                                    } ?>>Female</option>
                                    <option value="other" <?php if (($action == 'view' || $action == 'edit') && $employee['gender'] == 'other') {
                                        echo "selected";
                                    } ?>>Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="desg">Designation</label>
                                <input type="text" value="<?php if ($action == 'view' || $action == 'edit') {
                                    echo $employee['desg'];
                                } ?>" <?php if ($action == 'view') {
                                     echo "readonly='readonly'";
                                 } ?>  class="form-control" id="desg" placeholder="Enter designation" required name="desg">
                            </div>
                            <?php 
                        if($action == "view"){
                            // no button
                        }elseif($action == "edit"){
                            ?>
                        <button type="submit" class="btn btn-success" name="btn_submit">Update</button>
                        <?php
                        }else{
                            ?>
                        <button type="submit" class="btn btn-success" name="btn_submit">Submit</button>
                        <?php
                        }
                        ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="<?php echo EMS_PLUGIN_URL ?>js/jquery.min.js"></script>
    <script src="<?php echo EMS_PLUGIN_URL ?>js/bootstrap.min.js"></script>
    <script src="<?php echo EMS_PLUGIN_URL ?>js/jquery.validate.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            $('#add-employee').validate();
        });
    </script>
</body>

</html>