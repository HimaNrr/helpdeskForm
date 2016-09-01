<? 
include('auth.inc'); 
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

function assign_rand_value($num) {
 switch($num)  {
    case "1": $rand_value = "z"; break;
    case "2": $rand_value = "b";  break;
    case "3": $rand_value = "c";  break;
    case "4": $rand_value = "d";  break;
    case "5": $rand_value = "d";  break;
    case "6": $rand_value = "f"; break;
    case "7": $rand_value = "g"; break;
    case "8": $rand_value = "h"; break;
    case "9": $rand_value = "h";  break;
    case "10": $rand_value = "j"; break;
    case "11": $rand_value = "k";  break;
    case "12": $rand_value = "m";  break;
    case "13": $rand_value = "m";  break;
    case "14": $rand_value = "n";  break;
    case "15": $rand_value = "p";  break;
    case "16": $rand_value = "p";  break;
    case "17": $rand_value = "q";  break;
    case "18": $rand_value = "r";  break;
    case "19": $rand_value = "s";  break;
    case "20": $rand_value = "t";  break;
    case "21": $rand_value = "t";  break;
    case "22": $rand_value = "v";  break;
    case "23": $rand_value = "w";  break;
    case "24": $rand_value = "x";  break;
    case "25": $rand_value = "y";  break;
    case "26": $rand_value = "z";  break;
    case "27": $rand_value = "2";  break;
    case "28": $rand_value = "3";  break;
    case "29": $rand_value = "4";  break;
    case "30": $rand_value = "5";  break;
    case "31": $rand_value = "6";  break;
    case "32": $rand_value = "7";  break;
    case "33": $rand_value = "8";  break;
    case "34": $rand_value = "9";  break;
    case "35": $rand_value = "4";  break;
    case "36": $rand_value = "9"; break;
  }
  return $rand_value;
}
function get_rand_id($length) { 
  if($length>0) { 
    $rand_id=""; for($i=1; $i<=$length; $i++) {
      mt_srand((double)microtime() * 1000000); $num = mt_rand(1,36); $rand_id .= assign_rand_value($num);
    }
  }
  return $rand_id;
}




?><html><head><script type="text/javascript" src="/helpdesk/calendardate.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function editformcheck(str) {
  var at="@";
  var dot=".";
  var lat=str.indexOf(at);
  var lstr=str.length;
  var ldot=str.indexOf(dot);
  if (str.indexOf(at)==-1) { return false; }
  if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr) { return false; }
  if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr) { return false; }
  if (str.indexOf(at,(lat+1))!=-1) { return false; }
  if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot) { return false; }
  if (str.indexOf(dot,(lat+2))==-1) { return false; }
  if (str.indexOf(" ")!=-1) { return false; }
  return true          
}

function checkform(form)
{ 
  if (form.worker['EMAIL'].value == "") {  alert( "Please enter the email address." ); form.email.focus(); return false ; }
  if (form.worker['ACTIVE_DIRECTORY'].value == "") {  alert( "Please enter the Active directory username." ); form.active_directory.focus(); return false ; }
  if (editformcheck(form.worker['EMAIL'].value)==false) { alert("Email address should be formatted like: first.last@maine.gov"); return false; }
  return true ;
}
//-->
</script>
<? include('header.inc'); 
include('stripslashes.inc');

$worker=array();
$workerdb=array();
$workertype=array();
$dhsoffice=array(); 
$bureautype=array();
$subdivisiontype=array();
$apprecertskilltype=array();
$focusgrouptype=array();
$siebelpositiontype=array();


$accountstatustype=array();
$securitygroup=array();
$groupassign=array();
$groupassigndb=array();
$workermanagers=array();
$addperm=array();
$delperm=array();
$updatework=array();
$dolinterfaceid=array();

$worker=$_POST['worker'];
$workerdb=$_POST['workerdb'];
$workertype=$_POST['workertype'];
$dhsoffice=$_POST['dhsoffice'];
$bureautype=$_POST['bureautype'];
$subdivisiontype=$_POST['subdivisiontype'];
$apprecertskilltype=$_POST['apprecertskilltype'];
$focusgrouptype=$_POST['focusgrouptype'];
$siebelpositiontype=$_POST['siebelpositiontype'];


$accountstatustype=$_POST['accountstatustype'];
$dolinterfaceid=$_POST['dolinterfaceid'];
$securitygroup=$_POST['securitygroup'];
if ($_POST['groupassign']) {$groupassign=$_POST['groupassign'];}
if ($_POST['groupassigndb']) {$groupassigndb=$_POST['groupassigndb'];}
$workermanagers=$_POST['workermanagers'];
if ($_POST['addperm']) { $addperm=$_POST['addperm']; }
if ($_POST['delperm']) { $delperm=$_POST['delperm']; }
if ($_POST['updatework']) { $updatework=$_POST['updatework']; }
if ($workerdb['PASSWORD']=='no-login') { 
  $InCreateMode=1; 
  $modefour="created";
  $requiredfont1="<font color=red>";
  $requiredfont2="</font>";
} else {
  $modefour="updated";
  $requiredfont1="";
  $requiredfont2="";
}

if ($_POST['START_DT']) {$worker['START_DT']=$_POST['START_DT']; }
if ($_POST['END_DT']) {$worker['END_DT']=$_POST['END_DT']; }
$current=$_POST[current];
if ($current=="") {$current=1;} 
$btnNext='>>> NEXT >>>';
$btnNextCommit='>>> Commit These Changes >>>';
$btnBack='<<< BACK <<<';
if ($_POST['btnNext']==$btnNext) {$current=$current+1;}
if ($_POST['btnNext']==$btnNextCommit) {$current=$current+1;}
if ($_POST['btnBack']==$btnBack) {$current=$current-1;}

$workermanagers=stripslashes_deep($workermanagers);
$worker=stripslashes_deep($worker);

// Here we know we're a new account and that we're coming from the creation screen...
if (($workerdb['PASSWORD']=='no-login') && stripos($_SERVER['HTTP_REFERER'],'createtoedit')) {
  $groupassign['5']='ACES_SYSTEM_USER'; // this is a required, default permission
  }


?>
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
<? if ($current<4) { ?>
<? if (!$InCreateMode) { ?><h1 align="center">Edit Account</h1><? } else {
?><H1 align="center">Create Account</H1><? } ?>
<div align="center"><? print "<B><font color=green>Worker Account: ".$worker['FIRST_NAME']." ".$worker['MIDDLE_NAME']." ".$worker['LAST_NAME']." ".$worker['NAME_SUFFIX']." (".$worker['ACCOUNT'].")</center></font></B></div>";
?>
<P>
<div align="center">
<? $titles=array("Demographics", "ACES Security Roles", "Summary Confirmation");
for ($i=0;$i<sizeof($titles);$i++) {
  print ($current==$i+1?"<B>&gt;&gt;&gt; ":"").($i+1).". ".$titles[$i]." ".($current==$i+1?"&lt;&lt;&lt;</B> ":" "); 
  }
} 

if ($current==4) { 
  if ($InCreateMode) { print '<h1 align="center">Account Created</h1>'; 
    } else { print '<h1 align="center">Account Updated</h1>'; }
?>
<div align="center"><? print "<B><font color=green>Worker Account: ".$worker['FIRST_NAME']." ".$worker['MIDDLE_NAME']." ".$worker['LAST_NAME']." ".$worker['NAME_SUFFIX']." (".$worker['ACCOUNT'].")</center></font></B></div>";
?>
<P>
<?
} else {
  print "<P><form action=/helpdesk/edit/ method=post ".($current==1?"onsubmit=\"return checkform(this)\"":"")." >";
  print "<input type=hidden name=current value=".$current.">";
}
if ($current<4) { print '<table border="0">'; }
if ($current>1 && $current<4) {
  print "<input type=hidden name=box_failed_logins value=\"".($_POST['box_failed_logins']?"on":"")."\">";
  }

if ($current==4) {
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/helpdesk.php');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbconnecttest.php');
  $accountupdateok=1; // must start at 1
// oci8.persistent_timeout == 300
// oci8.ping_interval== 0

  if ($_POST['box_failed_logins']) { $updatework['FAILED_LOGINS']=0; } 

  foreach ($updatework as $key=>$value) {
    $query="";
    if ($key=="FAILED_LOGINS") { $query=$key."=:".$key; } else {
      if ($value<>"") { $query=$key."=:".$key; } else { $query=$key."=NULL"; }
    }
    if ($key=="ACCOUNT_STATUS_TYPE_CD") {$query=$query.",account_status_dt=sysdate"; }
    $query='update worker set '.$query.' where worker_id = :workerid';
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':workerid', $worker['WORKER_ID']);
    oci_bind_by_name($stid, ":".$key, $value);

//DEBUG
//print $query;
//print "key=".$key."<BR>value=".$value."<BR>";

    $r = oci_execute($stid, OCI_DEFAULT);
    if (!$r) {
      $accountupdateok++;
      $e = oci_error($stid);
      $to      = 'aces.support@maine.gov';
      $subject = '[ERROR] ACES HELPDESK TOOL';
      $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: "
                 .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9)\nError Message :\n".$e['message']."\n"
                 .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
      $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
      mail($to, $subject, $message, $headers);
    } // if error
  } // foreach updatework

  foreach ($addperm as $key=>$value) {
    $query = 'insert into group_assignment (worker_id,security_group_id) values (:workerid,:sgid)';
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':sgid', $key);
    oci_bind_by_name($stid, ':workerid', $worker['WORKER_ID']);
    $r = oci_execute($stid, OCI_DEFAULT);
    if (!$r) {
      $accountupdateok++;
      $e = oci_error($stid);
      $to      = 'aces.support@maine.gov';
      $subject = '[ERROR] ACES HELPDESK TOOL';
      $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: "
                 .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9)\nError Message :\n".$e['message']."\n"
                 .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s","^")."\n\n"
                 ."sgid=".$key."\nwid=".$worker['WORKER_ID']."\nhelpdesk worker=".$_COOKIE['HDTUSER']."\n";

      $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
      mail($to, $subject, $message, $headers);
    } // if error
  } // foreach addperm

  foreach ($delperm as $key=>$value) {
    $query = 'delete from group_assignment where worker_id = :workerid and security_group_id = :sgid';
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':sgid', $key);
    oci_bind_by_name($stid, ':workerid', $worker['WORKER_ID']);
    $r = oci_execute($stid, OCI_DEFAULT);
    if (!$r) {
      $accountupdateok++;
      $e = oci_error($stid);
      $to      = 'aces.support@maine.gov';
      $subject = '[ERROR] ACES HELPDESK TOOL';
      $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: "
                 .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9)\nError Message :\n".$e['message']."\n"
                 .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
      $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
      mail($to, $subject, $message, $headers);
    } // if error

    // we specifically allow zero here, it means the sgid and workerid combination is already gone
    if (oci_num_rows($stid)>1) { 
      $accountupdateok++;
      $to      = 'aces.support@maine.gov';
      $subject = '[ERROR] ACES HELPDESK TOOL';
      $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: "
                 .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9)\nkey=".$key."\nworkerid=".$worker['WORKER_ID']
                 ."\nnum_rows=".oci_num_rows($stid)."\n";
      $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
      mail($to, $subject, $message, $headers);
    } // if num rows > 1
  } // foreach delperm

  if (($accountupdateok==1) && ($workerdb['PASSWORD']<>'no-login')) {
    $query = "insert into worker_comment (worker_id,note,worker_id_helpdesk) values (:wid,:note,:hdwid)";
    $stid = oci_parse($conn, $query);
    $note = "Account information updated.";
    oci_bind_by_name($stid, ':wid', $worker['WORKER_ID']);
    oci_bind_by_name($stid, ':note', $note);
    oci_bind_by_name($stid, ':hdwid', $_COOKIE['HDTWID']);
    $r = @oci_execute($stid);
    if (!$r) {
      $accountupdateok++;
      $e = oci_error($stid);
      $to      = 'aces.support@maine.gov';
      $subject = '[ERROR] ACES HELPDESK TOOL - W_C';
      $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: "
                 .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9 Set Password)\nError Message :\n".$e['message']."\n"
                 .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
      $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
      mail($to, $subject, $message, $headers);
    } // if error
  } // if not a new create

  if (($accountupdateok==1) && ($workerdb['PASSWORD']=='no-login')) {
    if ($worker['EMAIL']<>"") {
      $query = "insert into worker_comment (worker_id,note,worker_id_helpdesk) values (:wid,:note,:hdwid)";
      $stid = oci_parse($conn, $query);
      $note = "Account created, worker emailed at [".$worker['EMAIL']."].";
      oci_bind_by_name($stid, ':wid', $worker['WORKER_ID']);
      oci_bind_by_name($stid, ':note', $note);
      oci_bind_by_name($stid, ':hdwid', $_COOKIE['HDTWID']);
      $r = @oci_execute($stid);
      if (!$r) {
        $accountupdateok++;
        $e = oci_error($stid);
        $to      = 'aces.support@maine.gov';
        $subject = '[ERROR] ACES HELPDESK TOOL - W_C';
        $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: "
                   .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9 Set Password)\nError Message :\n".$e['message']."\n"
                   .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
        $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
        mail($to, $subject, $message, $headers);
      } // if error
    } else {
      $query = "insert into worker_comment (worker_id,note,worker_id_helpdesk) values (:wid,:note,:hdwid)";
      $stid = oci_parse($conn, $query);
      $note = "Account created, worker not emailed, email address not supplied.";
      oci_bind_by_name($stid, ':wid', $worker['WORKER_ID']);
      oci_bind_by_name($stid, ':note', $note);
      oci_bind_by_name($stid, ':hdwid', $_COOKIE['HDTWID']);
      $r = @oci_execute($stid);
      if (!$r) {
        $accountupdateok++;
        $e = oci_error($stid);
        $to      = 'aces.support@maine.gov';
        $subject = '[ERROR] ACES HELPDESK TOOL - W_C';
        $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: "
                   .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9 Set Password)\nError Message :\n".$e['message']."\n"
                   .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
        $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
        mail($to, $subject, $message, $headers);
      } // if error
    }
  }


  if (($accountupdateok==1) && ($workerdb['PASSWORD']=='no-login') && ($worker['EMAIL']<>"")) {
    $password=get_rand_id(10);
    $epassword=base64_encode(pack("H*",md5($password)));
    $query = 'update worker set password=:password where worker_id = :workerid';
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':password', $epassword);
    oci_bind_by_name($stid, ':workerid', $worker['WORKER_ID']);
    $r = oci_execute($stid, OCI_DEFAULT);
    if (!$r) {
      $accountupdateok++;
      $e = oci_error($stid);
      $to      = 'aces.support@maine.gov';
      $subject = '[ERROR] ACES HELPDESK TOOL';
      $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: "
                 .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9 Set Password)\nError Message :\n".$e['message']."\n"
                 .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
      $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
      mail($to, $subject, $message, $headers);
    } // if error
$emailmessage="Hello,

This is an important message from Automated Client Eligibility System (ACES). 

Your new account has been created.

The ACES application is available at:

     https://aces.dhs.state.me.us/
     
The Fraud Hotline is available at:

     http://fraudhotline.state.me.us/     

Your account name is:
     ".$worker['ACCOUNT']."

Your initial, temporary password is:
     ".$password."

You will be required to change your password upon your first login;
please do so at your earliest convenience and contact the helpdesk 
if you have any additional questions.

Thank you.

-The ACES Team
";
    mail($worker['EMAIL'],"[ACES] Account Created",$emailmessage,"From: \"ACESHelp, Desk\" <Desk.ACESHelp@maine.gov>\n"); 
  } // if should we set password


  if ($accountupdateok==1) { 
    $committed = oci_commit($conn); 
    if (!committed) {
      $accountupdateok++;
      $e = oci_error($stid);
      $to      = 'aces.support@maine.gov';
      $subject = '[ERROR] ACES HELPDESK TOOL';
      $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nCOMMIT FAILURE!\nRequest URI: "
                 .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9)\nError Message :\n".$e['message']."\n"
                 .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
      $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
      mail($to, $subject, $message, $headers);
    } // if error
  } else {
    $rollbacked = oci_rollback($conn); 
    if (!rollbacked) {
      $accountupdateok++;
      $e = oci_error($stid);
      $to      = 'aces.support@maine.gov';
      $subject = '[ERROR] ACES HELPDESK TOOL';
      $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nROLLBACK FAILURE!\nRequest URI: "
                 .$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__." (~ -9)\nError Message :\n".$e['message']."\n"
                 .$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
      $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
      mail($to, $subject, $message, $headers);
    } // if error
  } // if-else accountupdateok==1



  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
?>
<div align="center">
<? if ($accountupdateok==1) { print "<B><font color=green>This account has been successfully ".$modefour.".</font></B><P>\n"; } 
   else { print "<font color=red><b>This account was NOT ".$modefour." succcessfully.</b><p>We've already been notified of this error but please continue below and check to insure the worker can access ACES correctly.</font></B><P>\n"; } ?>
<a href="/helpdesk/search/">[To Continue Click Here]</a></div><? 

} // end of current==4

if ($current==3) { 
  $wechangednothing=0;  // this is needed to block the next button if we didn't change anything

  foreach ($workerdb as $key=>$value) {
    if ($key<>'PASSWORD' && $key<>'MOD_DT' && $key<>'MOD_MODULE' && $key<>'MOD_USER' && $value<>$worker[$key]) {
      $wechangednothing=1; }
  } // foreach workerdb

  foreach ($groupassign as $key=>$value) {
    if ($wechangednothing<2 && $value<>$groupassigndb[$key]) { $wechangednothing=2+$wechangednothing;}
  } // foreach groupassigndb
  foreach ($groupassigndb as $key=>$value) {
    if ($wechangednothing<2 && $value<>$groupassign[$key]) { $wechangednothing=2+$wechangednothing;}
  } // foreach groupassigndb

  if ($wechangednothing>1) { ?>
<tr><th bgcolor=e0e0e0 colspan=3>Summary Of Changes</th></tr>
<? }

  if ($wechangednothing>2 || $wechangednothing==1) {?>
<tr>
 <th bgcolor=e0a0a0>Field</th>
 <th bgcolor=a0e0a0>Changed From</th>
 <th bgcolor=a0a0e0>Changed To</td>
</tr>
<? }
  foreach ($workerdb as $key=>$value) {
    if ($key<>'PASSWORD' && $key<>'MOD_DT' && $key<>'MOD_MODULE' && $key<>'MOD_USER' && $value<>$worker[$key]) {
      if ($key=='TEL_NUM' || $key=='TEL_EXT') { 
        $pattern=array('/[-\ ()+]/','/[A-Z]/','/[a-z]/');
        $worker[$key]=preg_replace($pattern,"",$worker[$key]); 
      } //if num
      print "<tr><td align=left>".preg_replace("/\_/"," ",$key).
            "</td><td align=center>".($value<>""?$value:"[Blank]").
            "</td><td align=center>".($worker[$key]<>""?$worker[$key]:"[Blank]").
            "</td></tr>"; 
      print "<input type=hidden name=\"updatework[$key]\" value=\"$worker[$key]\">\n";
    } // if not non-updatable field
  } // foreach workerdb

 if ($wechangednothing>1) { print "<tr><td colspan=3><hr></td></tr><tr>"; }

  foreach ($groupassign as $key=>$value) {
    if ($value<>$groupassigndb[$key]) {
      print "<tr><td align=left><font color=green><b>Assigned new permission:</b></font></td><td align=left colspan=2><font color=green><b>".$value."</b></font></td></tr>";
      print "<input type=hidden name=\"addperm[$key]\" value=\"".$value."\">\n"; 
    }
  } // foreach groupassigndb
  foreach ($groupassigndb as $key=>$value) {
    if ($value<>$groupassign[$key]) {
      print "<tr><td align=left><font color=orange><b>Revoked existing permission:</b></font></td><td align=left colspan=2><font color=orange><b>".$value."</b></font></td></tr>"; 
      print "<input type=hidden name=\"delperm[$key]\" value=\"".$value."\">\n"; 
    } 
  } // foreach groupassigndb


  if ($_POST['box_failed_logins']) {
    print "<tr><td colspan=3><hr></td></tr><tr><td colspan=3><center><b>Failed login count will be reset.</b></center></td></tr>\n";
    $wechangednothing=$wechangednothing+4;
  } // end of box failed logins update msg

  if ($wechangednothing==0) {
    print "<tr><td colspan=2><center><b>[No changes were made.]</b></center></td></tr>\n";
  }

} // end of $current==3

if ($current==2) { 
 $rowcolor=1;
  foreach ($securitygroup as $key=>$value) {// also CCDDFF works nicely
    print "<tr".($rowcolor==1?" bgcolor=\"#CCFFDD\"":" bgcolor=\"#FEFFEF\"").
          "><td colspan=3 align=right>".preg_replace("/\_/"," ",$value).
          ($value=="VENDOR_CROSSWALK_TOOL"?"<br>(Worker can access the Vendor Crosswalk tool.)":"").
          ($value=="MANAGE_OTHER_WORKERS"?"<br>(This worker is on the \"Worker Manager\" list.)":"").
          ($value=="ACES_ADMINISTRATION_TOOL"?"<br>(This worker has access to this tool.)":"").
          (1==2?"</td><td align=left>":"")."<input type=checkbox name=\"groupassign[$key]\" value=\"".$value."\" ".
          ($groupassign[$key]==$securitygroup[$key]?"checked":"")."></td><td bgcolor=\"#FFFFFF\">"."</td></tr>\n"; 
    $rowcolor=($rowcolor==1?2:1);
    } 
}

if ($current==1) { // PAGE 1

  print "<input type=hidden name=\"worker[ACCOUNT]\" value=\"".$worker[ACCOUNT]."\">\n";
  print "<input type=hidden name=\"worker[WORKER_ID]\" value=\"".$worker[WORKER_ID]."\">\n";
  print "<input type=hidden name=\"worker[PSWD_EXPIRE_DT]\" value=\"".$worker['PSWD_EXPIRE_DT']."\">\n";
  print "<input type=hidden name=\"worker[ACCOUNT_STATUS_DT]\" value=\"".$worker['ACCOUNT_STATUS_DT']."\">\n";
  print "<input type=hidden name=\"worker[FAILED_LOGINS]\" value=\"".$worker['FAILED_LOGINS']."\">\n";
  print "<tr><td><font color=brown><b><u>User Information</b></u></td><td colspan=2><hr color=brown>";
  print "</td></tr>\n<tr><td>";
print "<tr><td>".$requiredfont1."Last name: ".$requiredfont2."</td><td colspan=2><input name=\"worker[LAST_NAME]\" type=text value=\"".$worker['LAST_NAME']."\"></td></tr>\n<tr><td>";
  print $requiredfont1."First name: ".$requiredfont2."</td><td colspan=2><input name=\"worker[FIRST_NAME]\" type=text value=\"".$worker['FIRST_NAME']."\"></td></tr>\n<tr><td>";
  print "Middle name: </td><td colspan=2><input name=\"worker[MIDDLE_NAME]\" type=text value=\"".$worker['MIDDLE_NAME']."\"></td></tr>\n<tr><td>";
  print "Suffix: </td><td colspan=2><input name=worker[NAME_SUFFIX] type=text value=\"".$worker['NAME_SUFFIX']."\"></td></tr>\n<tr><td>";
  print $requiredfont1."Email: ".$requiredfont2."</td><td colspan=2><input name=worker[EMAIL] type=text value=\"".$worker['EMAIL']."\" size=45></td></tr>\n<tr><td>";
  print $requiredfont1."Active Directory: ".$requiredfont2."</td><td colspan=2><input name=worker[ACTIVE_DIRECTORY] type=text value=\"".$worker['ACTIVE_DIRECTORY']."\" size=45></td></tr>\n<tr><td>";
  print "Telephone: </td><td colspan=2><input name=worker[TEL_NUM] type=text value=\"".$worker['TEL_NUM']."\">";
  print "&nbsp; Ext: <input name=worker[TEL_EXT] type=text value=\"".$worker['TEL_EXT']."\" size=6></td></tr>\n<tr><td>";
  print "Worker Type: </td><td colspan=2><select name=\"worker[WORKER_TYPE_CD]\">";
  foreach ($workertype as $key=>$value) {
    print "\n   <option";
    if ($key==$worker['WORKER_TYPE_CD']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">"."(".$key.") ".$value;
    if ($key==$workerdb['WORKER_TYPE_CD']) { print " (Current)"; }
  }  
  print "</select></td></tr>\n<tr colspan=2><td>";
  print "DOL Interface ID: </td><td colspan=2><input name=worker[DOL_INTERFACE_ID] type=text value=\"".$worker['DOL_INTERFACE_ID']."\" size=45></td></tr>\n<tr><td>";

  print "Worker Manager: </td><td colspan=2><select name=\"worker[WORKER_ID_MANAGER]\">";
  print "\n   <option";
  if (!$worker['WORKER_ID_MANAGER']) { print " SELECTED"; }
  print " value=\"\">";
  if (!$worker['WORKER_ID_MANAGER']) { print "[Not Set] (Current)"; }

  foreach ($workermanagers as $key=>$value) {
    print "\n   <option";
    if ($key==$worker['WORKER_ID_MANAGER']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">".$value;
    if ($key==$workerdb['WORKER_ID_MANAGER']) { print " (Current)"; }
  }  
  print "</select></td></tr>\n<tr colspan=3><td>";

  print "Office: </td><td colspan=2><select name=\"worker[DHS_OFFICE_ID]\">";
  foreach ($dhsoffice as $key=>$value) {
    print "\n   <option";
    if ($key == $worker['DHS_OFFICE_ID']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">"."(".$key.") ".$value;
    if ($key==$workerdb['DHS_OFFICE_ID']) { print " (Current)"; }
  }
  print "</select></td></tr>\n<tr><td>";

  print "Bureau: </td><td colspan=2><select name=\"worker[BUREAU_TYPE_CD]'\">";
  foreach ($bureautype as $key=>$value) {
    print "\n   <option";
    if ($key== $worker['BUREAU_TYPE_CD']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">"."(".$key.") ".$value;
    if ($key==$workerdb['BUREAU_TYPE_CD']) { print " (Current)"; }
  }
  print "</select></td></tr>\n<tr><td>";

  print "Sub Division: </td><td colspan=2><select name=\"worker[SUBDIVISION_TYPE_CD]'\">";
  foreach ($subdivisiontype as $key=>$value) {
    print "\n   <option";
    if ($key== $worker['SUBDIVISION_TYPE_CD']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">"."(".$key.") ".$value;
    if ($key==$workerdb['SUBDIVISION_TYPE_CD']) { print " (Current)"; }
  }
  print "</select></td></tr>\n<tr><td>";

print "<font color=purple><b><u>Siebel Information</b></u> </td><td colspan=2><hr color=purple>";
  print "</td></tr>\n<tr><td>";

  print "Siebel Position: </td><td colspan=2><select name=\"worker[SIEBEL_POSITION_TYPE_CD]'\">";
  foreach ($siebelpositiontype as $key=>$value) {
    print "\n   <option";
    if ($key== $worker['SIEBEL_POSITION_TYPE_CD']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">"."(".$key.") ".$value;
    if ($key==$workerdb['SIEBEL_POSITION_TYPE_CD']) { print " (Current)"; }
  }
  print "</select></td></tr>\n<tr><td>";

  print "Applications/Recertifications: </td><td colspan=2><select name=\"worker[APP_RECERT_SKILL_TYPE_CD]'\">";
  foreach ($apprecertskilltype as $key=>$value) {
    print "\n   <option";
    if ($key== $worker['APP_RECERT_SKILL_TYPE_CD']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">"."(".$key.") ".$value;
    if ($key==$workerdb['APP_RECERT_SKILL_TYPE_CD']) { print " (Current)"; }
  }
  print "</select></td></tr>\n<tr><td>";

  print "Focus Group: </td><td colspan=2><select name=\"worker[FOCUS_GROUP_TYPE_CD]'\">";
  foreach ($focusgrouptype as $key=>$value) {
    print "\n   <option";
    if ($key== $worker['FOCUS_GROUP_TYPE_CD']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">"."(".$key.") ".$value;
    if ($key==$workerdb['FOCUS_GROUP_TYPE_CD']) { print " (Current)"; }
  }
  print "</select></td></tr>\n<tr><td>";


  print "Skills: </td><td colspan=2>";
    print"<input type=checkbox name=\"worker[TANF_SKILL_SET_IND]'\" ";
    if ($worker['TANF_SKILL_SET_IND']=='Y') { print " CHECKED"; }
    print " value=Y";
    print ">TANF";
    print"<input type=checkbox name=\"worker[LTC_SKILL_SET_IND]'\" ";
    if ($worker['LTC_SKILL_SET_IND']=='Y') { print " CHECKED"; }
    print " value=Y";
    print ">LTC";
    print"<input type=checkbox name=\"worker[OTHER_SKILL_SET_IND]'\" ";
    if ($worker['OTHER_SKILL_SET_IND']=='Y') { print " CHECKED"; }
    print " value=Y";
    print ">Other";
    print "</td></tr>\n<tr><td>";

print "<font color=orange><b><u>Account Information</b></u></td><td colspan=2><hr color=orange>";
  print "</td></tr>\n<tr><td>";

  print "Account Status: </td><td colspan=2><select name=\"worker[ACCOUNT_STATUS_TYPE_CD]\">";
  foreach ($accountstatustype as $key=>$value) {
    print "\n   <option";
    if ($key == $worker['ACCOUNT_STATUS_TYPE_CD']) { print " SELECTED"; }
    print " value=\"".$key."\"";
    print ">"."(".$key.") ".$value;
    if ($key==$workerdb['ACCOUNT_STATUS_TYPE_CD']) { print " (Current)"; }
  }
  print "</select></td></tr>\n<tr><td>";

  print "Password Expires: </td><td>".$worker['PSWD_EXPIRE_DT']."</td></tr>\n<tr><td>";
  print "Account Status Changed: </td><td colspan=2>".$worker['ACCOUNT_STATUS_DT']."</td></tr>\n<tr><td>";
  print "Account Start Date: </td><td colspan=2>"."<script>DateInput('START_DT', true, 'DD-MON-YYYY', '".$worker['START_DT']."')</script>"."</td></tr>\n<tr><td>";
  print "Account End Date: </td><td colspan=2>".
        "<script>DateInput('END_DT', false, 'DD-MON-YYYY'".($worker['END_DT']?", '".$worker['END_DT']."'":"").")</script>".
        "</td></tr>\n<tr><td>";
  print ($worker['FAILED_LOGINS']>0?"<font color=red>Failed Logins: </font></td><td><font color=red>".$worker['FAILED_LOGINS']."</font></td><td>( Reset Failed Logins: <input type=checkbox name=box_failed_logins ".($_POST['box_failed_logins']?"checked":"")." > )":"Failed Logins: </td><td>0")."</td></tr>";


} // if current=1

if ($current<3 || $wechangednothing>0) { print "<TR><TD colspan=3><HR></TD></TR>\n"; }
print "<tr><td>";
if ($current<>1 && $current<4) {
print '<input type=submit name="btnBack" id="btnBack" value="'.$btnBack.'">';
}
?>
<?
if ($current<3) {
?>
</td>
<td colspan=2 align=right>
<input type=submit name="btnNext" id="btnNext" value="<? print $btnNext; ?>">
<?
}
if ($current==3 && $wechangednothing>0) { ?>
</td>
<td colspan=2 align=right>
<input type=submit name="btnNext" id="btnNext" value="<? print $btnNextCommit; ?>">
<? }
?>
<?

if ($current<4) {
  print "</td></tr>\n";
  foreach ($workertype as $key=>$value) {print "<input type=hidden name=\"workertype[$key]\" value=\"".$value."\">\n"; } 
  foreach ($dhsoffice as $key=>$value) {print "<input type=hidden name=\"dhsoffice[$key]\" value=\"".$value."\">\n"; } 
  foreach ($bureautype as $key=>$value) {print "<input type=hidden name=\"bureautype[$key]\" value=\"".$value."\">\n"; } 
foreach ($subdivisiontype as $key=>$value) {print "<input type=hidden name=\"subdivisiontype[$key]\" value=\"".$value."\">\n"; } 
foreach ($apprecertskilltype as $key=>$value) {print "<input type=hidden name=\"apprecertskilltype[$key]\" value=\"".$value."\">\n"; } 
foreach ($focusgrouptype as $key=>$value) {print "<input type=hidden name=\"focusgrouptype[$key]\" value=\"".$value."\">\n"; } 
foreach ($siebelpositiontype as $key=>$value) {print "<input type=hidden name=\"siebelpositiontype[$key]\" value=\"".$value."\">\n"; } 

  foreach ($accountstatustype as $key=>$value) {print "<input type=hidden name=\"accountstatustype[$key]\" value=\"".$value."\">\n"; } 
  foreach ($workerdb as $key=>$value) {print "<input type=hidden name=\"workerdb[$key]\" value=\"".$value."\">\n"; } 
  foreach ($securitygroup as $key=>$value) {print "<input type=hidden name=\"securitygroup[$key]\" value=\"".$value."\">\n"; } 
  foreach ($groupassigndb as $key=>$value) {print "<input type=hidden name=\"groupassigndb[$key]\" value=\"".$value."\">\n"; }
  foreach ($workermanagers as $key=>$value) {print "<input type=hidden name=\"workermanagers[$key]\" value=\"$value\">\n"; } 
  if ($current<>1) { foreach ($worker as $key=>$value) {print "<input type=hidden name=\"worker[$key]\" value=\"".$value."\">\n"; } }
  if ($current<>2) { foreach ($groupassign as $key=>$value) {print "<input type=hidden name=\"groupassign[$key]\" value=\"".$value."\">\n"; } }
  print "</form></table></div>\n<P>";
  if (!$InCreateMode) { print '<div align="center"><a href="/helpdesk/search/">[Back To Search]</a></div>'; }

} // if current<4 
include('footer.inc');
?>
<? // print "<!-- <PRE>"; print_r($_POST); print "</PRE> -->"; ?>
<? //  print "<B>$current/$InCreateMode</B>";?>