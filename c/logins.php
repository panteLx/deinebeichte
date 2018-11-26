<?php
if (isset($_POST['reset'])) {
  mysqli_query($con,"truncate AdminLogins;");
}
?>
<link rel="stylesheet" href="assets/css/lib/datatable/dataTables.bootstrap.min.css">

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Logins</strong>
                <button type="button" class="btn btn-secondary sm-1 float-right" data-toggle="modal" data-target="#mediumModal">
                    Reset
                </button>
            </div>
            <div class="card-body">
                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                          <th>ID</th>
                          <th>Status</th>
                          <th>Username</th>
                          <th>IP Adresse</th>
                          <th>Standort</th>
                          <th>Zeitpunkt</th>
                        </tr>
                    </thead>
                    <tbody>
                  <?php
                  $SQLshow = mysqli_query($con,"SELECT * FROM AdminLogins");
                  while($row = mysqli_fetch_array($SQLshow)){
                  ?>
                <tr>
                  <td><?php echo $row[ID]; ?></td>
                  <td <?php if ($row[Status] == "SUCCESS") { ?>
                  style="color:green"<?php } elseif ($row[Status] == "FAILED") { ?>
                  style="color:red"<?php } ?>><?php echo $row[Status]; ?></td>
                  <td><?php echo $row[UserName]; ?></td>
                  <td><a href="http://ipinfo.io/<?php echo $row[IP]; ?>" target="_blank"><?php echo $row[IP]; ?></a></td>
                  <td><?php echo $row[Location]; ?></td>
                  <?php $date = date_create($row[LoginTime]); ?>
                  <td><?php echo date_format($date,"d.m.Y - H:i"); ?></td>
                </tr>
                <?php
                }
                ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Tabellenreset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                  <form action="" method="post" class="">
                    <div class="alert alert-danger" role="alert">
                      <center>Bist du sicher?</center>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <button type="submit" class="btn btn-primary" name="reset">Resetten</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="assets/js/lib/data-table/datatables.min.js"></script>
<script src="assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
<script src="assets/js/lib/data-table/dataTables.buttons.min.js"></script>
<script src="assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
<script src="assets/js/lib/data-table/jszip.min.js"></script>
<script src="assets/js/lib/data-table/vfs_fonts.js"></script>
<script src="assets/js/lib/data-table/buttons.html5.min.js"></script>
<script src="assets/js/lib/data-table/buttons.print.min.js"></script>
<script src="assets/js/lib/data-table/buttons.colVis.min.js"></script>
<script src="assets/js/init/datatables-init.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
      $('#bootstrap-data-table-export').DataTable();
  } );
</script>
