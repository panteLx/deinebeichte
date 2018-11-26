<?php
if (isset($_POST["del"])) {
      $id = $_POST['getid'];
    	mysqli_query($con,"DELETE FROM members WHERE memberID='$id'");
      echo "<script language='javascript'>
      document.location='c.php?c=users';</script>"; }

?>


<div class="card">
<div class="card-body">
    <h3 class="card-title">Userverwaltung</h3>
</div>
</div>
<div class="row">
<?php
$SQLshow = mysqli_query($con,"SELECT * FROM members");
while($row = mysqli_fetch_array($SQLshow)){
?>

<div class="col-md-4">
                        <aside class="profile-nav alt">
                            <section class="card">
                                <div class="card-header user-header alt bg-dark">
                                    <div class="media">
                                        <div class="media-body">
                                          <center>
                                            <h2 class="text-light display-6"><?php echo $row[username]; ?></h2>
                                            <p><?php echo $row[role]; ?></p>
                                          </center>
                                        </div>
                                    </div>
                                </div>


                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <i class="fa fa-envelope-o"></i> E-Mail: <?php echo $row[email]; ?><span class="badge badge-primary pull-right">ID: <?php echo $row[memberID]; ?></span>
                                    </li>
                                    <li class="list-group-item">
                                      <i class="fa fa-tasks"></i> Status: <?php if ($row[active] == 'Yes') { echo "User Aktiviert"; } elseif ($row[active] == 'Disabled') { echo "User <b>gesperrt</b>"; } else { echo "User nicht Aktiviert"; } ?>
                                    </li>
                                    <li class="list-group-item">
                                      <center>
                                      <form action="" method='post'>
                                        <input type="hidden" name="getid" value="<?php echo $row[memberID]; ?>"></input>
                                        <a href="#edit<?php echo $row[memberID];?>" data-toggle="modal">
                                            <button type='button' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span>Bearbeiten</button>
                                        </a>
                                      <button type="submit" class="btn btn-danger btn-sm" name="del">Löschen</button>
                                      </form>
                                    </center>
                                    </li>
                                </ul>

                            </section>
                        </aside>
                    </div>
<?php } ?>
</div>
<?php
if (isset($_POST['create'])) {
  $edit_id = $_POST['edit_id'];
            $insert = "UPDATE members SET username='$_POST[username]', email='$_POST[email]', role='$_POST[role]' WHERE memberID='$edit_id'";
            if (!mysqli_query($con, $insert)) {
            echo "Insert Error: " .mysqli_error($con);
            }
            mysqli_close($con);
            echo "<script language='javascript'>
            document.location='c.php?c=users';</script>";
            }

if (isset($_POST['aktivierung'])) {
              $edit_id = $_POST['edit_id'];
  $insert = "UPDATE members SET active='Yes' WHERE memberID='$edit_id'";
  if (!mysqli_query($con, $insert)) {
  echo "Insert Error: " .mysqli_error($con);
  }
  echo "<script language='javascript'>
  document.location='c.php?c=users';</script>";
}
if (isset($_POST['deaktivierung'])) {
              $edit_id = $_POST['edit_id'];
  $insert = "UPDATE members SET active='Disabled' WHERE memberID='$edit_id'";
  if (!mysqli_query($con, $insert)) {
  echo "Insert Error: " .mysqli_error($con);
  }
  echo "<script language='javascript'>
  document.location='c.php?c=users';</script>";
}

$SQLshow = mysqli_query($con,"SELECT * FROM members");
        while($row = mysqli_fetch_array($SQLshow)) {
            ?>

<div id="edit<?php echo $row[memberID]; ?>" class="modal fade" role="dialog">
    <form method="post" class="form-horizontal" role="form">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">User <strong><?php echo $row['username']; ?></strong> Bearbeiten</h4>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="edit_id" value="<?php echo $row[memberID]; ?>"></input>
                  <div class="row form-group">
  <div class="col col-md-3"><label class=" form-control-label">User Aktivierung</label></div>
  <div class="col-12 col-md-9">
    <?php if ($row['active'] == 'Yes') { ?>
      <form method="post">
      <button type="submit" class="btn btn-primary" name="deaktivierung">Deaktiviere User</button>
    </form>
  <?php } else { ?>
    <form method="post">
    <button type="submit" class="btn btn-primary" name="aktivierung">Aktiviere User</button>
  </form>
<?php } ?>
  </div>
</div>
                <div class="row form-group">
                  <div class="col col-md-3"><label for="text-input" class=" form-control-label">Username</label></div>
                  <div class="col-12 col-md-9"><input type="text" id="text-input" name="username" class="form-control" value="<?php echo $row[username]; ?>"></div>
                </div>
                <div class="row form-group">
                  <div class="col col-md-3"><label for="email-input" class=" form-control-label">E-Mail</label></div>
                  <div class="col-12 col-md-9"><input type="email" id="email-input" name="email" class="form-control" value="<?php echo $row[email]; ?>"></div>
                </div>
                <div class="row form-group">
                  <div class="col col-md-3"><label for="select" class=" form-control-label">Rolle</label></div>
                  <div class="col-12 col-md-9">
                    <select id="select" class="form-control" name="role">
                      <option name="Admin" value="Admin" <?php if ($row['role'] == 'Admin') { ?>
                        selected
                      <?php } ?>>Admin</option>
                      <option name="Mod" value="Mod" <?php if ($row['role'] == 'Mod') { ?>
                        selected
                      <?php } ?>>Mod</option>
                      <option name="User" value="User" <?php if ($row['role'] == 'User') { ?>
                        selected
                      <?php } ?>>User</option>
                    </select>
                  </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary" name="create"><span class="glyphicon glyphicon-edit"></span> Bearbeiten</button>
                </div>
                </div>
            </div>
    </form>
</div>
<?php } ?>
