<?php
if (isset($_POST["del"])) {
      $id = $_POST['getid'];
    	mysqli_query($con,"DELETE FROM Whitelist WHERE ID='$id'");
       }

?>

      <div class="card">
      <div class="card-body">
        <button type="button" class="btn btn-secondary sm-1 float-right" data-toggle="modal" data-target="#mediumModal">
            Hinzufügen
        </button>
          <h3 class="card-title">Whitelisted IPs (Login)</h3>
    </div>
  </div>



<?php
if (isset($_POST['add'])) {
if (!filter_var($_POST[IP], FILTER_VALIDATE_IP) === false) {
  // make sure the ip address is unique
  $query = mysqli_query($con,"SELECT * FROM Whitelist WHERE IP = '$_POST[IP]'");
  $match = mysqli_num_rows($query);
  if ($match > 0){
    echo "<script language='javascript'>alert('IP Adresse ist bereits whitelisted.'); </script>";
    exit();
  }
  mysqli_query($con,"INSERT INTO Whitelist (`id` ,`IP`, `reason`) VALUES (NULL, '$_POST[IP]', '$_POST[Reason]')");
  mysqli_close($con);
  echo "<script language='javascript'>
document.location='./c.php?c=whitelist';</script>";
} else {
  echo "<script language='javascript'>alert('$_POST[IP] ist keine gültige IP Adresse.'); </script>";
}
}
?>
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">IP Whitelisten</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                  <form action="" method="post" class="">
                          <div class="form-group"><label for="ip" class=" form-control-label">IP Adresse</label><input type="text" id="ip" placeholder="IP Adresse eingeben..." class="form-control" name="IP" required></div>
                          <div class="form-group"><label for="reason" class=" form-control-label">Name</label><input type="text" id="reason" placeholder="Name eingeben..." class="form-control" name="Reason" required></div>
                      <p> <b>Beachte:</b> Whitelisted IPs können sich auch bei deaktivierter Anmeldung anmelden.</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <button type="submit" class="btn btn-primary" name="add">Whitelisten</button>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="row">
<?php
$SQLshow = mysqli_query($con,"SELECT * FROM Whitelist");
while($row = mysqli_fetch_array($SQLshow)){
?>
<div class="col-md-4">
  <div class="card">
  <div class="card-header bg-white">
  <blockquote class="blockquote mb-0">
  <p class="text-dark"><?php echo $row['reason']; ?></p>
  <footer class="blockquote-footer text-dark"><a class="text-dark" href="http://ipinfo.io/<?php echo $row[IP]; ?>" target="_blank"><?php echo $row[IP]; ?></a></footer>
  </blockquote>
  <br>
  <form action="" method='post'>
  <input type="hidden" name="getid" value="<?php echo $row[ID]; ?>"></input>
  <button type="submit" class="btn btn-danger btn-sm" name="del">Löschen</button>
  </form>
</div>

</div>
</div>
<?php } ?>
</div>
