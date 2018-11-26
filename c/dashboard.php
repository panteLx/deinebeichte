

<?php
if (isset($_POST["activate"])) {
  $insert = "UPDATE Wartung SET Active='AKTIVIERT'";
  if (!mysqli_query($con, $insert)) {
  echo "Insert Error: " .mysqli_error($con);
  // END SERVERDATABASE INSERT
}
}
if (isset($_POST["deactivate"])) {
  $insert = "UPDATE Wartung SET Active='DEAKTIVIERT'";
  if (!mysqli_query($con, $insert)) {
  echo "Insert Error: " .mysqli_error($con);
  // END SERVERDATABASE INSERT
}
}
if (isset($_POST["activatewhitelist"])) {
  $insert = "UPDATE WhitelistMode SET Status='AKTIVIERT'";
  if (!mysqli_query($con, $insert)) {
  echo "Insert Error: " .mysqli_error($con);
  // END SERVERDATABASE INSERT
}
}
if (isset($_POST["deactivatewhitelist"])) {
  $insert = "UPDATE WhitelistMode SET Status='DEAKTIVIERT'";
  if (!mysqli_query($con, $insert)) {
  echo "Insert Error: " .mysqli_error($con);
  // END SERVERDATABASE INSERT
}
}

  $sql = "SELECT Active FROM Wartung";
  $wartung = mysqli_query($con, $sql);

  $sql = "SELECT Status FROM WhitelistMode";
  $whitelist = mysqli_query($con, $sql);

 $sql = "SELECT news, uhr FROM panelnews";
 $result = mysqli_query($con, $sql);
      // output data of each row
?>
<div class="row">
<?php
$SQLshow = mysqli_query($con,"SELECT * FROM members WHERE username = '$_SESSION[username]'");
while($row = mysqli_fetch_array($SQLshow)){
  if ($row['role'] == 'Admin' || $row['role'] == 'Mod') { ?>
<?php
$sql=mysqli_query($con, "SELECT count(*) as member from members");
$data=mysqli_fetch_assoc($sql);
?>
<div class="col-lg-3">
    <div class="card">
        <div class="card-body">
            <div class="stat-widget-one">
                <div class="stat-icon dib"><i class="ti-user text-primary border-primary"></i></div>
                <div class="stat-content dib">
                    <div class="stat-text">Users</div>
                    <div class="stat-digit"><?php echo $data['member']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$sql=mysqli_query($con, "SELECT count(*) as abeichten from Data WHERE Public='TRUE'");
$data=mysqli_fetch_assoc($sql);
?>
<div class="col-lg-3">
    <div class="card">
        <div class="card-body">
            <div class="stat-widget-one">
                <div class="stat-icon dib"><i class="ti-check text-success border-success"></i></div>
                <div class="stat-content dib">
                    <div class="stat-text">Beantwortete Beichten</div>
                    <div class="stat-digit"><?php echo $data['abeichten']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$sql=mysqli_query($con, "SELECT count(*) as abeichten from Data WHERE Public='FALSE'");
$data=mysqli_fetch_assoc($sql);
?>
<div class="col-lg-3">
    <div class="card">
        <div class="card-body">
            <div class="stat-widget-one">
                <div class="stat-icon dib"><i class="ti-close text-danger border-danger"></i></div>
                <div class="stat-content dib">
                    <div class="stat-text">Offene Beichten</div>
                    <div class="stat-digit"><?php echo $data['abeichten']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$sql=mysqli_query($con, "SELECT count(*) as beichten from Data");
$data=mysqli_fetch_assoc($sql);
?>
<div class="col-lg-3">
    <div class="card">
        <div class="card-body">
            <div class="stat-widget-one">
                <div class="stat-icon dib"><i class="ti-pencil text-info border-info"></i></div>
                <div class="stat-content dib">
                    <div class="stat-text">Gesamte Beichten</div>
                    <div class="stat-digit"><?php echo $data['beichten']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

 <?php while($row = mysqli_fetch_object($result)) { ?>
<div class="row">
<div class="col-md-12">
<div class="card">
    <div class="card-header">
        <strong class="card-title">Interne Notizen</strong>
    </div>
    <div class="card-body">
        <div class="alert alert-info" role="alert">
            <p><?php echo $row->news; ?></p>
            <hr>
            <p class="mb-0">Letztes Update <?php echo $row->uhr; ?></p>
        </div>
    </div>
</div>
</div>
</div>

<?php } ?>
<div class="row">
<?php while($row = mysqli_fetch_object($wartung)) { ?>
<div class="col-sm-6">
  <div class="card">
      <div class="card-body">
        <form action="#" method="post">
        <h3>Neue Beichten verbieten (<?php echo $row->Active; ?>)</h3>
        <p>Wenn Aktiviert, können keine neuen Beichten von Usern verfasst werden.</p>
        <?php if ($row->Active == 'AKTIVIERT') { ?>
        <button type="submit" class="btn btn-primary" name="deactivate"><b>Deaktivieren</b></button>
      <?php } elseif ($row->Active == 'DEAKTIVIERT') { ?>
                <button type="submit" class="btn btn-danger" name="activate"><b>Aktivieren</b></button>
              <?php } ?>
        </form>
      </div>
    </div>
  </div>
<?php } ?>
<?php while($row = mysqli_fetch_object($whitelist)) { ?>
  <div class="col-sm-6">
  <div class="card">
      <div class="card-body">
        <form action="#" method="post">
        <h3>Anmeldungen verbieten (<?php echo $row->Status; ?>)</h3>
        <p>Wenn Aktiviert, können sich User nicht mehr anmelden.</p>
        <?php if ($row->Status == 'AKTIVIERT') { ?>
        <button type="submit" class="btn btn-primary" name="deactivatewhitelist"><b>Deaktivieren</b></button>
      <?php } elseif ($row->Status == 'DEAKTIVIERT') { ?>
                <button type="submit" class="btn btn-danger" name="activatewhitelist"><b>Aktivieren</b></button>
              <?php } ?>
        </form>
      </div>
    </div>
  </div>
<?php }
} } ?>
</div>
