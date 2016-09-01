<?
include('auth.inc');
include('stripslashes.inc');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/helpdesk.php');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbconnect.php');

  if ($_GET['txtSearch']) { // we were called from the account creation page, initialize and find account 
    $searchstring = trim(strtolower($_GET['txtSearch']));
    if (strlen($searchstring)<3) { header("Location: /helpdesk/search/?error=3&txtSearch=".$_GET['txtSearch']); exit; }
    $searchstring=stripslashes_deep($searchstring);
    $searchstring = explode(" ", $searchstring);
    for ($i=0;$i<sizeof($searchstring);$i++){
      if (($searchstring[$i]<>'') && (!preg_match('/[\']+$/', $searchstring[$i]))&& (!preg_match('/^[\'\-.a-z0-9]+$/i', $searchstring[$i])))  {header("Location: /helpdesk/search/?error=0"); exit;}
    }

  $query = 'select account,worker_id from worker where (account= :account or lower(first_name) like :first or lower(last_name) like :last) ';
   
  for ($i=1;$i<sizeof($searchstring);$i++){   
    $query=$query.' and (account like :account'.$i.' or lower(first_name) like :first'.$i.' or lower(last_name) like :last'.$i.') ';
  }
   
  $stid = oci_parse($conn, $query);
  $searchaccount=$searchstring[0];
  $searchstring[0]=$searchstring[0]."%";
  oci_bind_by_name($stid,"account",$searchaccount);
  oci_bind_by_name($stid,"first",$searchstring[0]);
  oci_bind_by_name($stid,"last",$searchstring[0]);

  for ($i=1;$i<(sizeof($searchstring)<4?sizeof($searchstring):3);$i++){
    $searchstring[$i]=$searchstring[$i]."%";
    oci_bind_by_name($stid,"account".$i,$searchstring[$i]);
    oci_bind_by_name($stid,"first".$i,$searchstring[$i]);
    oci_bind_by_name($stid,"last".$i,$searchstring[$i]);
  }


    if (!$stid) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
    $r = oci_execute($stid, OCI_DEFAULT);
    if (!$r) { $e = oci_error($stid); echo htmlentities($e['message']); exit; }
    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
      $account=$row['ACCOUNT'];
    }
    if (oci_num_rows($stid)>1) { header("Location: /helpdesk/search/?error=3&txtSearch=".$_GET['txtSearch']); exit;}
    if (oci_num_rows($stid)<1) { header("Location: /helpdesk/search/?error=1&txtSearch=".$_GET['txtSearch']); exit;}
    if ($account=='restricted' || $account=='SYSTEM_ALERT_CREATOR' || $account=='batch') { header("Location: /helpdesk/search/"); exit;}
  } else { header("Location: /helpdesk/search/"); exit; }

  if ($account=="") { header("Location: /helpdesk/search/"); exit; }


  /* define arrays for data */
  $workertype=array();
  $dhsoffice=array(); 
  $bureautype=array();
  $accountstatustype=array();
  $securitygroup=array();
  $groupassignment=array();
  $worker=array();
  $workermanagers=array();
  
  /* populate $workertype */
  $query = "SELECT worker_type_cd,worker_title from worker_type";
  $st = oci_parse($conn, $query);
  if (!$st) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $results = oci_execute($st, OCI_DEFAULT);
  if (!$results) { $e = oci_error($st); echo htmlentities($e['message']); exit; }
  while ($thisrow = oci_fetch_array($st, OCI_RETURN_NULLS+OCI_ASSOC)) {
    $workertype[$thisrow['WORKER_TYPE_CD']]=$thisrow['WORKER_TITLE']; 
  }
  /* populate $dhsoffice */
  $query = "SELECT dhs_office_id,name from dhs_office where name <> 'OBSOLETE -- DO NOT USE'";
  $st = oci_parse($conn, $query);
  if (!$st) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $results = oci_execute($st, OCI_DEFAULT);
  if (!$results) { $e = oci_error($st); echo htmlentities($e['message']); exit; }
  while ($thisrow = oci_fetch_array($st, OCI_RETURN_NULLS+OCI_ASSOC)) {
    $dhsoffice[$thisrow['DHS_OFFICE_ID']]=$thisrow['NAME']; 
  }

  /* populate $bureautype */
  $query = "SELECT BUREAU_TYPE_CD,description from BUREAU_TYPE";
  $st = oci_parse($conn, $query);
  if (!$st) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $results = oci_execute($st, OCI_DEFAULT);
  if (!$results) { $e = oci_error($st); echo htmlentities($e['message']); exit; }
  while ($thisrow = oci_fetch_array($st, OCI_RETURN_NULLS+OCI_ASSOC)) {
    $bureautype[$thisrow['BUREAU_TYPE_CD']]=$thisrow['DESCRIPTION']; 
  }

  /* populate $accountstatustype */
  $query = "SELECT ACCOUNT_STATUS_TYPE_CD,description from ACCOUNT_STATUS_TYPE";
  $st = oci_parse($conn, $query);
  if (!$st) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $results = oci_execute($st, OCI_DEFAULT);
  if (!$results) { $e = oci_error($st); echo htmlentities($e['message']); exit; }
  while ($thisrow = oci_fetch_array($st, OCI_RETURN_NULLS+OCI_ASSOC)) {
    $accountstatustype[$thisrow['ACCOUNT_STATUS_TYPE_CD']]=$thisrow['DESCRIPTION']; 
  }

  $query = "SELECT * from worker where account = :account ";
  $st = oci_parse($conn, $query);
  oci_bind_by_name($st, ":account", $account);
  if (!$st) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $results = oci_execute($st, OCI_DEFAULT);
  if (!$results) { $e = oci_error($st); echo htmlentities($e['message']); exit; }
  $ncols = oci_num_fields($st);
  while ($thisrow = oci_fetch_array($st, OCI_RETURN_NULLS+OCI_ASSOC)) {
    for ($i = 1; $i <= $ncols; $i++) {
     $worker[ oci_field_name($st, $i) ] = $thisrow[ oci_field_name($st, $i) ];
    }
  }
/* if oci_num_rows($st)<>1 we've got a problem.. */


  $query = "select sg.name,sg.security_group_id
 from security_group sg
 where sg.security_group_id<>100
 order by sg.name";
  $st = oci_parse($conn, $query);
  oci_bind_by_name($st, ":workerid", $worker['WORKER_ID']);
  if (!$st) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $results = oci_execute($st, OCI_DEFAULT);
  if (!$results) { $e = oci_error($st); echo htmlentities($e['message']); exit; }
  $ncols = oci_num_fields($st);
  while ($thisrow = oci_fetch_array($st, OCI_RETURN_NULLS+OCI_ASSOC)) {
    $securitygroup[$thisrow['SECURITY_GROUP_ID']] = $thisrow['NAME'];
//    if ($thisrow['SECURITY_GROUP_ID']==5) { $groupassignment[$thisrow['SECURITY_GROUP_ID']] = $thisrow['NAME']; }
  }


  $query = "SELECT worker_id,first_name,middle_name,last_name,name_suffix from worker w where".
           " worker_id>999 and (worker_id in (select worker_id_manager from worker) or ".
           " worker_id = (select worker_id from group_assignment where security_group_id=98 and ".
           " worker_id = w.worker_id)) order by last_name,first_name,name_suffix ";
  $st = oci_parse($conn, $query);
  if (!$st) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $results = oci_execute($st, OCI_DEFAULT);
  if (!$results) { $e = oci_error($st); echo htmlentities($e['message']); exit; }
  $ncols = oci_num_fields($st);
  while ($thisrow = oci_fetch_array($st, OCI_RETURN_NULLS+OCI_ASSOC)) {
    $workermanagers[$thisrow['WORKER_ID']] = $thisrow['LAST_NAME'].($thisrow['NAME_SUFFIX']?(' '.$thisrow['NAME_SUFFIX']):"").", ".$thisrow['FIRST_NAME'].(($thisrow['MIDDLE_NAME'])?(' '.$thisrow['MIDDLE_NAME']):"");
  }

  $workermanagers=stripslashes_deep($workermanagers);
  $worker=stripslashes_deep($worker);

  /* debugging print_r of data */
/*
  print "<!--";
  print "<HR><PRE>workertype\n";print_r($workertype);  print "</PRE>\n";
  print "<HR><PRE>dhsoffice\n";print_r($dhsoffice);  print "</PRE>\n";
  print "<HR><PRE>bureautype\n";print_r($bureautype);  print "</PRE>\n";
  print "<HR><PRE>accountstatustype\n";print_r($accountstatustype);  print "</PRE>\n";

  print "<HR><PRE>worker\n";print_r($worker);  print "</PRE>\n";

  print "<HR><PRE>securitygroup\n";print_r($securitygroup);  print "</PRE>\n";
  print "<HR><PRE>groupassignment\n";print_r($groupassignment);  print "</PRE>\n";
  print "<HR><PRE>workermanagers\n";print_r($workermanagers);  print "</PRE>\n";
  print " -->";?>
*/
if ($worker['FIRST_NAME']==" ") { $worker['FIRST_NAME']=""; }
if ($worker['LAST_NAME']==" ") { $worker['LAST_NAME']=""; }

    header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
    header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
    header( "Cache-Control: no-cache, must-revalidate" );
    header( "Pragma: no-cache" );

?>
<html><head><? include('header.inc'); ?>
<style type="text/css" media="screen">
    body {
        font: 11px arial;
    }

    #spanr {
    text-align: right; color: green;
    }

    #spanl {
    text-align: left; color: red;
    }
 
    .suggest_link {
        background-color: #FFFFFF;
        padding: 2px 6px 2px 6px;
    }
 
    #search_suggest_title {
        visibility: hidden;
    }

    .suggest_link_over {
        background-color: #5588DD;
        padding: 2px 6px 2px 6px;
    }
 
    #search_suggest {
        position: center;  
        background-color: #FFFFFF; 
        text-align: left; 
        border: 1px solid #000000;
        font-size:12px;
        width:240px; 
        padding:1px 1px 1px 1px;
        visibility:hidden;
    }
</style>
</head>
<body>
<h1 align="center">Account Creation</h1>
<P>
<div align="center">
<form action=/helpdesk/edit method=post name="AutoForm">
<?
foreach ($workertype as $key=>$value) {print "<input type=hidden name=\"workertype[$key]\" value=\"".$value."\">\n"; } 
foreach ($dhsoffice as $key=>$value) {print "<input type=hidden name=\"dhsoffice[$key]\" value=\"".$value."\">\n"; } 
foreach ($bureautype as $key=>$value) {print "<input type=hidden name=\"bureautype[$key]\" value=\"".$value."\">\n"; } 
foreach ($accountstatustype as $key=>$value) {print "<input type=hidden name=\"accountstatustype[$key]\" value=\"".$value."\">\n"; } 
foreach ($worker as $key=>$value) {
  print "<input type=hidden name=\"workerdb[$key]\" value=\"".$value."\">\n"; 
  print "<input type=hidden name=\"worker[$key]\" value=\"".$value."\">\n"; 
} 
foreach ($securitygroup as $key=>$value) {print "<input type=hidden name=\"securitygroup[$key]\" value=\"".$value."\">\n"; } 
foreach ($groupassignment as $key=>$value) {
  print "<input type=hidden name=\"groupassigndb[$key]\" value=\"".$value."\">\n"; 
  print "<input type=hidden name=\"groupassign[$key]\" value=\"".$value."\">\n"; 
} 
foreach ($workermanagers as $key=>$value) {print "<input type=hidden name=\"workermanagers[$key]\" value=\"$value\">\n"; } 
?>
<noscript>
<input type=submit value="Click Here To Continue..."><br>(This may take a few moments to load.)
</noscript>
</form> 
<script type="text/javascript" language="JavaScript"><!--
document.AutoForm.submit();
//--></script>
</div>
<P>
<?
include('footer.inc');
?>
