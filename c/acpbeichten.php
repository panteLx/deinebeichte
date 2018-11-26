<?php
if (isset($_POST["del"])) {
      $id = $_POST['getid'];
    	mysqli_query($con,"DELETE FROM Data WHERE ID='$id'");
      echo "<script language='javascript'>
      document.location='c.php?c=acpbeichten';</script>";
    }

      if (isset($_POST["private"])) {
            $id = $_POST['getid'];
          	mysqli_query($con,"UPDATE Data SET Public='FALSE' WHERE ID='$id'");
            echo "<script language='javascript'>
            document.location='c.php?c=acpbeichten';</script>";
          }
?>

      <div class="card">
      <div class="card-body">
          <h3 class="card-title">Offene Beichten</h3>
    </div>
  </div>

<?php 					$SQLshow = mysqli_query($con,"SELECT * FROM Data WHERE Public!='TRUE' ORDER BY ID DESC");
          while($row = mysqli_fetch_array($SQLshow)) { ?>

<div class="card">
<div class="card-header bg-white">
<blockquote class="blockquote mb-0">
<p class="text-dark"><?php $cleanout = str_replace("\n", '<br />', $row['Message']); echo $cleanout; ?></p>
<footer class="blockquote-footer text-dark"><small><?php $date = date_create($row[Time]); echo date_format($date,"d.m.Y - H:i"); ?> | <a href="http://ipinfo.io/<?php echo $row[IP]; ?>" target="_blank"><?php echo $row[IP]; ?></a> | <?php echo $row[Browser]; ?></small></footer>
</blockquote>
<br>
<form action="" method='post'>
  <input type="hidden" name="getid" value="<?php echo $row[ID]; ?>"></input>
  <a href="#edit<?php echo $row[ID];?>" data-toggle="modal">
      <button type='button' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span>Antworten</button>
  </a>
<button type="submit" class="btn btn-danger btn-sm" name="del">Löschen</button>
</form>
</div>
</div>
<?php } ?>


<?php
            if (isset($_POST['create'])) {
            $antwort = $_POST['antwort'];
            $antwort1 = mysqli_real_escape_string($con, $antwort);
            $head = $_POST['head'];
            $head1 = mysqli_real_escape_string($con, $head);
            $message = $_POST['message'];
            $message1 = mysqli_real_escape_string($con, $message);
            $edit_id = $_POST['edit_id'];
            $insert = "UPDATE Data SET Answer='$antwort1', Public='TRUE', Headline='$head1', Category='$_POST[category]', Message='$message1', Author='$_SESSION[username]' WHERE ID='$edit_id'";
            if (!mysqli_query($con, $insert)) {
            echo "Insert Error: " .mysqli_error($con);
            }
            mysqli_close($con);
            echo "<script language='javascript'>
            document.location='c.php?c=acpbeichten';</script>";
            }
            $SQLshow = mysqli_query($con,"SELECT * FROM Data WHERE Public!='TRUE'");
                      while($row = mysqli_fetch_array($SQLshow)) {
            ?>

<div id="edit<?php echo $row[ID]; ?>" class="modal fade" role="dialog">
    <form method="post" class="form-horizontal" role="form">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Beichte veröffentlichen</h4>
                </div>
                <div class="modal-body">

      <div class="row form-group">
    <div class="col col-md-3"><label for="textarea-input" class=" form-control-label">Beichte</label></div>
    <input type="hidden" name="edit_id" value="<?php echo $row[ID]; ?>"></input>
    <div class="col-12 col-md-9"><textarea name="message" id="textarea-input" rows="5"  class="form-control"><?php echo $row['Message'];  ?></textarea></div>
  </div>
  <div class="row form-group">
    <div class="col col-md-3"><label for="select" class=" form-control-label">Kategorie</label></div>
    <div class="col-12 col-md-9">
      <select id="select" class="form-control" name="category">
        <option name="Beichte" value="Beichte" selected>Beichte</option>
        <option name="Kontaktanzeige" value="Kontaktanzeige">Kontaktanzeige</option>
        <option name="Changelog" value="Changelog">Changelog</option>
      </select>
    </div>
  </div>
  <div class="row form-group">
    <div class="col col-md-3">
    <label for="exampleInputPassword1">Überschrift</label></div>
    <div class="col-12 col-md-9">
    <input type="text" class="form-control" name="head" required value="<?php $cleanout = str_replace("\n", '<br />', $row['Headline']); echo $cleanout;  ?>" placeholder="Deine Überschrift..."></div>
  </div>

  <div class="row form-group">
    <div class="col col-md-3"><label for="textarea-input" class=" form-control-label">Antwort</label></div>
    <div class="col-12 col-md-9"><textarea name="antwort" id="textarea-input" rows="5" placeholder="Deine Antwort..." class="form-control" required><?php echo $row['Answer'];  ?></textarea></div>
  </div>
  <p><strong>Beichte #<?php echo $row[ID]; ?> | Eingesendet: <?php $date = date_create($row[Time]); echo date_format($date,"d.m.Y - H:i"); ?> | IP: <?php echo $row[IP]; ?></strong></p>
</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary" name="create"><span class="glyphicon glyphicon-edit"></span> Veröffentlichen</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php } ?>

<?php
$query=mysqli_query($con,"select count(ID) from Data WHERE Public = 'TRUE'");
$row = mysqli_fetch_row($query);

$rows = $row[0];

$page_rows = 5;

$last = ceil($rows/$page_rows);

if($last < 1){
  $last = 1;
}

$pagenum = 1;

if(isset($_GET['pn'])){
  $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}

if ($pagenum < 1) {
  $pagenum = 1;
}
else if ($pagenum > $last) {
  $pagenum = $last;
}

$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

$nquery=mysqli_query($con,"SELECT * FROM Data WHERE Public='TRUE' ORDER BY Time DESC $limit");

$paginationCtrls = '';

if($last != 1){

if ($pagenum > 1) {
      $previous = $pagenum - 1;
  $paginationCtrls .= '<li class="page-item"><a class="page-link" href="c.php?c=acpbeichten&pn=1">Erste Seite</a></li>';

  for($i = $pagenum-4; $i < $pagenum; $i++){
    if($i > 0){
          $paginationCtrls .= '<li class="page-item"><a class="page-link" href="c.php?c=acpbeichten&pn='.$i.'">'.$i.'</a></li>';
    }
    }
  }

$paginationCtrls .= '<li class="page-item active"><a class="page-link" href="c.php?c=acpbeichten&pn='.$pagenum.'">'.$pagenum.' </a></li>';

for($i = $pagenum+1; $i <= $last; $i++){
  $paginationCtrls .= '<li class="page-item"><a class="page-link" href="c.php?c=acpbeichten&pn='.$i.'">'.$i.' </a></li>';
  if($i >= $pagenum+4){
    break;
  }
}

  if ($pagenum != $last) {
      $next = $pagenum + 1;
      $paginationCtrls .= '<li class="page-item"><a class="page-link" href="c.php?c=acpbeichten&pn='.$last.'">Letzte Seite</a></li> ';
  }
}
?>

<div class="card bg-dark">
<p></p>
</div>

<div class="card">
<div class="card-body">
    <h3 class="card-title">Veröffentlichte Beichten/Kontaktanzeigen</h3>
</div>
</div>

<?php while($crow = mysqli_fetch_array($nquery)){ ?>

<div class="card">
<div class="card-header bg-white">
<blockquote class="blockquote mb-0">
<p class="text-dark"><?php $cleanout = str_replace("\n", '<br />', $crow['Message']); echo $cleanout; ?></p>
<p class="text-dark"><i><?php $cleanout = str_replace("\n", '<br />', $crow['Answer']); echo $cleanout; ?></i></p>
<footer class="blockquote-footer text-dark"><small><?php echo $crow['Category']; ?> | <?php $date = date_create($crow[Time]); echo date_format($date,"d.m.Y - H:i"); ?> | <a href="http://ipinfo.io/<?php echo $crow[IP]; ?>" target="_blank"><?php echo $crow[IP]; ?></a> | <?php echo $crow[Browser]; ?></small></footer>
</blockquote>
<br>
<form action="" method='post'>
  <input type="hidden" name="getid" value="<?php echo $crow[ID]; ?>"></input>
  <button type="submit" class="btn btn-primary btn-sm" name="private">Zurückziehen</button>
  <button type="submit" class="btn btn-danger btn-sm" name="del">Löschen</button>
</form>
</div>
</div>
<?php } ?>
<ul class="pagination" id="pagination_controls">
<?php echo $paginationCtrls; ?>
</ul>
