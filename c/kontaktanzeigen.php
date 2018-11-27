<?php

	$query=mysqli_query($con,"select count(ID) from Data WHERE Public = 'TRUE' AND Category = 'Kontaktanzeige'");
	$row = mysqli_fetch_row($query);

	$rows = $row[0];

	$page_rows = 10;

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

	$nquery=mysqli_query($con,"SELECT * FROM Data WHERE Public='TRUE' AND Category = 'Kontaktanzeige' ORDER BY Time DESC $limit");

	$paginationCtrls = '';

	if($last != 1){

	if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<li class="page-item"><a class="page-link" href="c.php?c=kontaktanzeigen&pn=1">Erste Seite</a></li>';

		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<li class="page-item"><a class="page-link" href="c.php?c=kontaktanzeigen&pn='.$i.'">'.$i.'</a></li>';
			}
	    }
    }

	$paginationCtrls .= '<li class="page-item active"><a class="page-link" href="c.php?c=kontaktanzeigen&pn='.$pagenum.'">'.$pagenum.' </a></li>';

	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<li class="page-item"><a class="page-link" href="c.php?c=kontaktanzeigen&pn='.$i.'">'.$i.' </a></li>';
		if($i >= $pagenum+4){
			break;
		}
	}

    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= '<li class="page-item"><a class="page-link" href="c.php?c=kontaktanzeigen&pn='.$last.'">Letzte Seite</a></li> ';
    }
	}

?>

<?php while($crow = mysqli_fetch_array($nquery)){ ?>
	<div class="card">
		<div class="card-body">
			<div class="card-title">
      <span class="badge badge-danger float-right mt-1"><?php echo $crow['Time']; ?></span>
			<h2><a href="./c.php?c=view&id=<?php echo $crow[ID]; ?>"><?php echo $crow['Headline']; ?></a></h2>
			</div><!--news_item_title-->
				<div class="card-content">
				<i><?php $cleanout = str_replace("\n", '<br />', $crow['Message']); echo $cleanout;  ?></i>
				</div><!--item_content-->
        <br>
				<a href="./c.php?c=view&id=<?php echo $crow[ID]; ?>"><button type="button" class="btn btn-sm btn-primary">Zur ganzen Anzeige</button></a>
				<hr>
				<p><b>Teilen</b></p>
				<a href="whatsapp://send?text=https://deinebeichte.com/c.php?c=view%26id%3D<?php echo $crow[ID]; ?>"><img src="./images/whatsapp.png" alt="WhatsApp" width="79" height="20"></a>
		</div><!--item_wrapper-->
	</div><!--feature_news_item-->
<?php } ?>
<ul class="pagination" id="pagination_controls">
<?php echo $paginationCtrls; ?>
</ul>
