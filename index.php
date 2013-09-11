<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="vendor/twbs/bootstrap/assets/ico/favicon.png">

	<title>Laravel Seeder</title>

	<!-- Bootstrap core CSS -->
	<link href="vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<style type="text/css">
	body {
		padding-top: 40px;
		padding-bottom: 40px;
		background-color: #eee;
	}
	</style>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="vendor/twbs/bootstrap/assets/js/html5shiv.js"></script>
	  <script src="vendor/twbs/bootstrap/assets/js/respond.min.js"></script>
	<![endif]-->
	</head>
	<body>
	<div class="container">
		<div class="well">
			<h2>Generate database seed file for laravel</h2>
			<p>Enter the form below and click Generate</p>
		</div>

	<div class="well">
	<form method="post" class="form-inline" title="lvgenerate" role="form">
	<div class="form-group">
	    <label class="sr-only" for="dbusername">DB Username</label>
	    <input name="dbusername" type="text" class="form-control" id="dbusername" placeholder="Enter Username" value="<?php echo isset($_POST['dbusername'])?$_POST['dbusername']:'' ?>">
	</div>
	<div class="form-group">
	    <label class="sr-only" for="dbpassword">DB Password</label>
	    <input name="dbpassword" type="password" class="form-control" id="dbpassword" placeholder="Enter Password" value="<?php echo isset($_POST['dbpassword'])?$_POST['dbpassword']:'' ?>">
	</div>
	<div class="form-group">
	    <label class="sr-only" for="dbhost">DB Host</label>
	    <input name="dbhost" type="text" class="form-control" id="dbhost" placeholder="Database Host" value="<?php echo isset($_POST['dbhost'])?$_POST['dbhost']:'' ?>">
	</div>
	<div class="form-group">
	    <label class="sr-only" for="dbname">DB Name</label>
	    <input name="dbname" type="text" class="form-control" id="dbname" placeholder="Database Name" value="<?php echo isset($_POST['dbname'])?$_POST['dbname']:'' ?>">
	</div>
	<div class="form-group">
	    <label class="sr-only" for="dbtable">DB Table</label>
	    <input name="dbtable" type="text" class="form-control" id="dbtable" placeholder="Table Name" value="<?php echo isset($_POST['dbtable'])?$_POST['dbtable']:'' ?>">
	</div>
	<button type="submit" class="btn btn-default">Generate</button>
	</form>
	</div>

<?php
// Include ezSQL core
include_once "vendor/jv2222/ezsql/shared/ez_sql_core.php";
// Include ezSQL database specific component
include_once "vendor/jv2222/ezsql/mysql/ez_sql_mysql.php";

// Initialise database object and establish a connection
// at the same time - db_user / db_password / db_name / db_host
if ( isset($_POST) && !empty($_POST['dbusername']) ) {
	
	$dbhost = $_POST['dbhost'];
	$dbusername = $_POST['dbusername'];
	$dbpassword = $_POST['dbpassword'];
	$dbname = $_POST['dbname'];
	$dbtable = $_POST['dbtable'];

	$db = new ezSQL_mysql($dbusername,$dbpassword,$dbname,$dbhost);
}

$seeds = $db->get_results("SELECT * FROM $dbtable",ARRAY_A);
//$db->debug();
?>

<div class="well">

<form class="form-horizontal" role="form">
	<div class="form-group">
		<label for="filename" class="col-lg-2 control-label">Filename</label>
		<div class="col-lg-10">
		<input type="text" class="form-control" id="filename" value="<?php echo ucwords($dbtable); ?>TableSeeder.php">
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail1" class="col-lg-2 control-label">Data</label>
		<div class="col-lg-10">
		<textarea class="form-control" rows="20">
&lt;?php
/**
 * Seed <?php echo $dbtable ?> table
 */
class <?php echo ucwords($dbtable); ?>TableSeeder extends Seeder {</p>

	public function run()
	{
		DB::table('<?php echo $dbtable ?>')-&gt;delete();
		$<?php echo $dbtable ?> = array(
<?php
if ($seeds){
	foreach ($seeds as $seed) {
		echo "\t\t\tarray(\n";
		unset($seed['id']);
		unset($seed['created_at']);
		unset($seed['updated_at']);

		foreach ($seed as $key => $value) {
			echo "\t\t\t\t'".$key."'"."=>".'"'.$value.'"'.",\n";
		}

		echo "\t\t\t\t'created_at' => new DateTime,\n";
		echo "\t\t\t\t'updated_at' => new DateTime,\n";		
		echo "\t\t\t".'),'."\n";
	}
}
?>
		);
		DB::table('<?php echo $dbtable ?>')->insert( $<?php echo $dbtable ?> );
	}
}
</textarea>
		</div>
	</div>
</form>

	</div>

	</div>
	</body>
</html>
