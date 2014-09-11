<?php 

define('ALLOW_ACCESS', true); // allow access to this page
require_once("includes/header.php"); 
$db = new Database;

$page = 1;
if (isset($_GET["page"])) {
	$page = ($_GET["page"] < 1) ? 1 : $_GET["page"];
}
$limit = 50;
if (isset($_GET["limit"])) {
	$limit = $_GET["limit"];
}
$offset = ($page-1)*$limit;

if (isset($_GET["email"])) {
	$db->query("SELECT count(id) as total FROM log_login WHERE email=:email ");
	$db->bind(':email', $_GET["email"]);
	$count = $db->single();

	$db->query('SELECT date_time, status, ip_address, browser, session_id, javascript as js FROM log_login WHERE email=:email ORDER BY id DESC LIMIT '.$limit.' OFFSET '.$offset);
	$db->bind(':email', $_GET["email"]);
	$logs = $db->fetchAll();
}


?>

<h1 class="page-header">User Log <small><?php echo $_GET["email"] ?> <span class="badge"><?php echo $count["total"]; ?></span></small></h1>

<div class="btn-group btn-group-sm pull-right">
	<a class="btn btn-default <?php echo ($limit == 10) ? "active" : "" ; ?>" href="?email=<?php echo $_GET["email"] ?>&page=1&limit=10">View 10/Page</a>
	<a class="btn btn-default <?php echo ($limit == 50) ? "active" : "" ; ?>" href="?email=<?php echo $_GET["email"] ?>&page=1&limit=50">View 50/Page</a>
	<a class="btn btn-default <?php echo ($limit == 100) ? "active" : "" ; ?>" href="?email=<?php echo $_GET["email"] ?>&page=1&limit=100">View 100/Page</a>
	<a class="btn btn-default <?php echo ($limit > 100) ? "active" : "" ; ?>" href="?email=<?php echo $_GET["email"] ?>&page=1&limit=999999999999999999">View All</a>
</div>

<div id="container" >
	<?php if ($logs): ?>

		<?php if ($count["total"] > $limit): ?>
			<ul class="pagination">
			  <li><a href="?email=<?php echo $_GET["email"] ?>&page=<?php echo ($page-1) ?>&limit=<?php echo $limit ?>">&laquo;</a></li>
				<?php for ($i=1; $i <= ceil($count["total"]/$limit); $i++) { ?>
					<li <?php echo ($i == $page) ? "class='active'" : ""; ?>><a href="?email=<?php echo $_GET["email"] ?>&page=<?php echo $i ?>&limit=<?php echo $limit ?>"><?php echo $i ?></a></li>
				<?php } ?>
			  <li <?php echo ($page == ceil($count["total"]/$limit)) ? "class='disabled'" : ""; ?>><a <?php echo ($page == ceil($count["total"]/$limit)) ? 'onclick="return false;"' : ""; ?> href="?email=<?php echo $_GET["email"] ?>&page=<?php echo ($page+1) ?>&limit=<?php echo $limit ?>">&raquo;</a></li>
			</ul>
		<?php endif ?>

		<div class="table-responsive">
			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<?php foreach ($logs[0] as $key => $value): ?>
						<th><?php echo $key ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=0; $i < count($logs); $i++) { ?>
						<tr>
							<?php foreach ($logs[$i] as $key => $value): ?>
								<?php if ($key == "ip_address"): ?>
									<td><a target="blank" href="http://www.infosniper.net/index.php?ip_address=<?php echo $value ?>"><?php echo $value ?></a></td>
								<?php elseif ($key == "status"): ?>
									<td><span class="label label-<?php echo ($value == "success") ? "success" : "danger" ; ?>"><?php echo $value ?></span></td>									
								<?php else: ?>
									<td><?php echo $value ?></td>
								<?php endif ?>
							<?php endforeach ?>
						</tr>
					<? } ?>
				</tbody>
			</table>
		</div>


		<?php if ($count["total"] > $limit): ?>
		<ul class="pagination">
		  <li><a href="?email=<?php echo $_GET["email"] ?>&page=<?php echo ($page-1) ?>&limit=<?php echo $limit ?>">&laquo;</a></li>
			<?php for ($i=1; $i <= ceil($count["total"]/$limit); $i++) { ?>
				<li <?php echo ($i == $page) ? "class='active'" : ""; ?>><a href="?email=<?php echo $_GET["email"] ?>&page=<?php echo $i ?>&limit=<?php echo $limit ?>"><?php echo $i ?></a></li>
			<?php } ?>
		  <li <?php echo ($page == ceil($count["total"]/$limit)) ? "class='disabled'" : ""; ?>><a <?php echo ($page == ceil($count["total"]/$limit)) ? 'onclick="return false;"' : ""; ?> href="?email=<?php echo $_GET["email"] ?>&page=<?php echo ($page+1) ?>&limit=<?php echo $limit ?>">&raquo;</a></li>
		</ul>
		<?php endif ?>
	
	<?php else: ?>
		<p class="well well-lg">No Log information found</p>
	<?php endif ?>

</div>

<?php require_once("includes/footer.php"); ?>