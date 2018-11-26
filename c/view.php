<?php

if (isset($_POST['create'])) {

  $comment = $_POST['comment'];
  $comment1 = mysqli_real_escape_string($con, $comment);
  $id = $_GET['id'];
  $session = $_SESSION['username'];
  $role = $_SESSION['role'];

  $insert = "INSERT INTO Comments (BeitragID, MemberName, Comment, Role) VALUES ('$id', '$session', '$comment1', '$role')";
  if (!mysqli_query($con, $insert)) {
  echo "Insert Error: " .mysqli_error($con);
}
}


$id = $_GET['id'];
    $SQLshow = mysqli_query($con,"SELECT * FROM Data WHERE ID = '$id' AND Public = 'TRUE'");
     while($row = mysqli_fetch_array($SQLshow)) {
?>



    <div class="card">
        <div class="card-header">
            <strong class="card-title"><?php echo $row['Headline']; ?><span class="badge badge-danger float-right mt-1"><?php echo $row['Category']; ?> vom <?php echo $row['Time']; ?></span></strong>
        </div>
        <div class="card-body">
            <p class="card-text"><?php $cleanout = str_replace("\n", '<br />', $row['Message']); echo $cleanout;  ?></p>
            <hr>
            <blockquote class="blockquote mb-0">
            <footer class="blockquote-footer"><i><?php $cleanout = str_replace("\n", '<br />', $row['Answer']); echo $cleanout;  ?></i></footer>
          </blockquote>
          <br>
            <center><a href="whatsapp://send?text=https://deinebeichte.com/c.php?c=view%26id%3D<?php echo $row[ID]; ?>"><img src="./images/whatsapp.png" alt="WhatsApp" width="79" height="20"></a></center>
        </div>
    </div>

    <div class="card">
  		<div class="card-body">
            <div class="card-title strong"><h4>Kommentare</h4><br></div>

        <?php     $SQLshow = mysqli_query($con,"SELECT * FROM Comments WHERE BeitragID = '$id' OR BeitragID = 'Alle'");
             while($row = mysqli_fetch_array($SQLshow)) { ?>
        <div class="card border border-secondary">
          <div class="card-body">
          <blockquote class="blockquote mb-0">
          <p><?php echo $row['Comment']; ?></p>
          <footer class="blockquote-footer"><i><small><strong><?php echo $row['MemberName']; ?></strong> am <?php echo $row['Time']; ?></small></i></footer>
        </blockquote>
      </div>
    </div>
  <?php } ?>
      </div>
    </div>

<?php } ?>

<div class="card">
  <div class="card-body">
    <div class="card-title strong"><h4>Schreibe ein Kommentar</h4><br></div>
  <?php if(!$user->is_logged_in()){ ?>
    <div class="alert alert-primary" role="alert">
        <center>Melde dich an um ein Kommentar zu verfassen. Jetzt <a href="login.php" class="alert-link">Anmelden</a>.</center>
    </div>
  <?php } elseif($user->is_logged_in()){ ?>
          <form method="POST" onsubmit="if (this.rules.checked == false) { alert ('Bitte bestätige unsere Datenschutzbestimmungen unter deiner Beichte!'); return false; } else { return true; }">
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="comment" required></textarea>
            <br>
            <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="rules" id="defaultCheck1">
            <label class="form-check-label" for="defaultCheck1">
            Ich akzeptiere die Datenschutzbestimmungen & AGBs
            </label>
                Zu unseren <a href="c.php?c=datenschutz" style="color:red;">Datenschutzbestimmungen</a> | Zu unseren <a href="c.php?c=agb" style="color:red;">AGBs</a></p>

          </div>
        				<button class="btn btn-primary" name="create">Absenden</button>
        	</form>
        </div>
	 <?php } ?>
      </div><!--feature_news_item-->
