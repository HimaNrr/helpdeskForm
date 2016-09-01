<? 
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/helpdesk.php');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbconnect.php');
  $searchstring = strtolower($_GET['q']);
 
  if (strlen($searchstring)==0) { print "3\n"; exit; } 

  if (!preg_match('/^[a-z0-9]+$/i', $searchstring)) {print "4\n"; exit;}

  if (strlen($searchstring)<4) { print "2\n"; exit; } 

  if (strlen($searchstring)>20) { print "6\n"; exit; } 

  $query = 'select count(*) from worker where account = \''.$searchstring.'\'';
  $stid = oci_parse($conn, $query);
  if (!$stid) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $r = oci_execute($stid, OCI_DEFAULT);
  if (!$r) { $e = oci_error($stid); echo htmlentities($e['message']); exit; }
  while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
    print ($row['COUNT(*)']==1?"1":"0")."\n";
  }
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
?>