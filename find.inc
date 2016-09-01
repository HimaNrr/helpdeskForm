<? 
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/helpdesk.php');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbconnect.php');

  $searchstring = strtolower($_GET['q']);
  $searchstring = stripslashes($searchstring);
//  $searchstring=html_entity_decode($searchstring);
 
  if (strlen($searchstring)<3) { exit; } # at least type in two characters! 


  $searchstring = explode(" ", $searchstring);

// commented out until ' handling is fixed
  for ($i=0;$i<sizeof($searchstring);$i++){
    if (($searchstring[$i]<>'') && (!preg_match('/[\']+$/i', $searchstring[$i]))&& (!preg_match('/^[\'\-.a-z0-9]+$/i', $searchstring[$i]))) {exit;}
  }

//  for ($i=0;$i<sizeof($searchstring);$i++){   print "\n".$searchstring[$i]."<BR>"; }

  $query = 'select worker_id,account,first_name,last_name from worker where (account like :account or lower(first_name) like :first or lower(last_name) like :last) ';

   
  for ($i=1;$i<sizeof($searchstring);$i++){   
    $query=$query.' and (account like :account'.$i.' or lower(first_name) like :first'.$i.' or lower(last_name) like :last'.$i.') ';
  }
   
  $stid = oci_parse($conn, $query);
  $searchstring[0]=$searchstring[0]."%";
  oci_bind_by_name($stid,"account",$searchstring[0]);
  oci_bind_by_name($stid,"first",$searchstring[0]);
  oci_bind_by_name($stid,"last",$searchstring[0]);

  for ($i=1;$i<sizeof($searchstring);$i++){
    $searchstring[$i]=$searchstring[$i]."%";
    oci_bind_by_name($stid,"account".$i,$searchstring[$i]);
    oci_bind_by_name($stid,"first".$i,$searchstring[$i]);
    oci_bind_by_name($stid,"last".$i,$searchstring[$i]);
  }



  if (!$stid) { $e = oci_error($conn); print htmlentities($e['message']); exit; }

  $r = oci_execute($stid, OCI_DEFAULT);
  if (!$r) { $e = oci_error($stid); echo htmlentities($e['message']); exit; }

  while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
    if ($row['WORKER_ID']>999) {
      print $row['ACCOUNT']." ".$row['FIRST_NAME']." ".$row['LAST_NAME']."\n";
    }
  }
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');


print "\n";

?>