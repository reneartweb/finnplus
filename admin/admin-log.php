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

if (isset($_GET["employee"])) {
	$db->query("SELECT count(id) as total FROM log_admin WHERE employee=:employee LIMIT 1");
	$db->bind(':employee', $_GET["employee"]);
	$count = $db->single();

	$db->query("SELECT name  FROM users WHERE id=:employee LIMIT 1");
	$db->bind(':employee', $_GET["employee"]);
	$user = $db->single();

	$db->query('SELECT date_time, status, message, ip_address, browser, session_id FROM log_admin WHERE employee=:employee ORDER BY id DESC LIMIT '.$limit.' OFFSET '.$offset);
	$db->bind(':employee', $_GET["employee"]);
	$logs = $db->fetchAll();
}


?>

<h1 class="page-header">Employee Activity Log <small><?php echo $user["name"] ?> <span class="badge"><?php echo $count["total"]; ?></span></small></h1>

<div class="btn-group btn-group-sm pull-right">
	<a class="btn btn-default <?php echo ($limit == 10) ? "active" : "" ; ?>" href="?employee=<?php echo $_GET["employee"] ?>&page=1&limit=10">View 10/Page</a>
	<a class="btn btn-default <?php echo ($limit == 50) ? "active" : "" ; ?>" href="?employee=<?php echo $_GET["employee"] ?>&page=1&limit=50">View 50/Page</a>
	<a class="btn btn-default <?php echo ($limit == 100) ? "active" : "" ; ?>" href="?employee=<?php echo $_GET["employee"] ?>&page=1&limit=100">View 100/Page</a>
	<a class="btn btn-default <?php echo ($limit > 100) ? "active" : "" ; ?>" href="?employee=<?php echo $_GET["employee"] ?>&page=1&limit=999999999999999999">View All</a>
</div>

<div id="container" >
	<?php if ($logs): ?>

		<?php if ($count["total"] > $limit): ?>
			<ul class="pagination">
			  <li><a href="?employee=<?php echo $_GET["employee"] ?>&page=<?php echo ($page-1) ?>&limit=<?php echo $limit ?>">&laquo;</a></li>
				<?php for ($i=1; $i <= ceil($count["total"]/$limit); $i++) { ?>
					<li <?php echo ($i == $page) ? "class='active'" : ""; ?>><a href="?employee=<?php echo $_GET["employee"] ?>&page=<?php echo $i ?>&limit=<?php echo $limit ?>"><?php echo $i ?></a></li>
				<?php } ?>
			  <li <?php echo ($page == ceil($count["total"]/$limit)) ? "class='disabled'" : ""; ?>><a <?php echo ($page == ceil($count["total"]/$limit)) ? 'onclick="return false;"' : ""; ?> href="?employee=<?php echo $_GET["employee"] ?>&page=<?php echo ($page+1) ?>&limit=<?php echo $limit ?>">&raquo;</a></li>
			</ul>
		<?php endif ?>

		<div class="table-responsive">
			<table class="table table-condensed admin-log-table">
				<thead>
					<tr>
						<?php foreach ($logs[0] as $key => $value): ?>
							<?php if ($key == "status"): ?>
								<?php continue; ?>
							<?php elseif ($key == "date_time"): ?>
								<th style="min-width:91px;"><?php echo $key ?></th>
							<?php else: ?>
								<th><?php echo $key ?></th>
							<?php endif ?>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=0; $i < count($logs); $i++) { ?>
						<tr>
							<?php foreach ($logs[$i] as $key => $value): ?>
								<?php if ($key == "status"): ?>
									<?php $status = $value; continue; ?>
								<?php elseif ($key == "message"): ?>
									<td><p class="well well-sm btn-<?php echo $status ?> active"><?php echo $value ?></p></td>
								<?php elseif ($key == "ip_address"): ?>
									<td><a target="blank" href="http://www.infosniper.net/index.php?ip_address=<?php echo $value ?>"><?php echo $value ?></a></td>
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
		  <li><a href="?employee=<?php echo $_GET["employee"] ?>&page=<?php echo ($page-1) ?>&limit=<?php echo $limit ?>">&laquo;</a></li>
			<?php for ($i=1; $i <= ceil($count["total"]/$limit); $i++) { ?>
				<li <?php echo ($i == $page) ? "class='active'" : ""; ?>><a href="?employee=<?php echo $_GET["employee"] ?>&page=<?php echo $i ?>&limit=<?php echo $limit ?>"><?php echo $i ?></a></li>
			<?php } ?>
		  <li <?php echo ($page == ceil($count["total"]/$limit)) ? "class='disabled'" : ""; ?>><a <?php echo ($page == ceil($count["total"]/$limit)) ? 'onclick="return false;"' : ""; ?> href="?employee=<?php echo $_GET["employee"] ?>&page=<?php echo ($page+1) ?>&limit=<?php echo $limit ?>">&raquo;</a></li>
		</ul>
		<?php endif ?>
	
	<?php else: ?>
		<p class="well well-lg">No Log information found</p>
	<?php endif ?>

</div>

<?php require_once("includes/footer.php"); ?>