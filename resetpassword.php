<? 
include('auth.inc');
$worker=$_POST['worker'];
if (($_POST['_resetpassword']) && ($_POST['email1'] == $_POST['email2']) && $_POST['email1']<>""
&& !(eregi("\r",$from)) && !(eregi("\n",$from))) {
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

$password=get_rand_id(10);
$epassword=base64_encode(pack("H*",md5($password)));

  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/helpdesk.php');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbconnect.php');

  $query = "update worker set account_status_type_cd='A' where worker_id=:workerid and account_status_type_cd='S'";

  $stid = oci_parse($conn, $query);

  oci_bind_by_name($stid, ':workerid', $worker['WORKER_ID']);

  $r = @oci_execute($stid);
  if (!$r) {
    print "<font color=red><b>Error on statement execution.</b></font><BR>Please report this error message:<BR>";
    $e = oci_error($stid); print htmlentities($e['message']); print "<BR>";
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
    exit;
  }

  $query = "update worker set email=:email,failed_logins=0,password=:password,pswd_expire_dt=sysdate-1 where worker_id=:workerid";
  $stid = oci_parse($conn, $query);
  oci_bind_by_name($stid, ':workerid', $worker['WORKER_ID']);
  oci_bind_by_name($stid, ':email', $_POST['email1']);
  oci_bind_by_name($stid, ':password', $epassword);

  $r = @oci_execute($stid);
  if (!$r) {
    print "<font color=red><b>Error on statement execution.</b></font><BR>Please report this error message:<BR>";
    $e = oci_error($stid); print htmlentities($e['message']); print "<BR>";
    include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
    exit;
  }

  $query = "insert into worker_comment (worker_id,note,worker_id_helpdesk) values (:wid,:note,:hdwid)";
  $stid = oci_parse($conn, $query);
  $note = "Password reset, temporary password [".$password."] emailed to [".$_POST['email1']."].";
  oci_bind_by_name($stid, ':wid', $worker['WORKER_ID']);
  oci_bind_by_name($stid, ':note', $note);
  oci_bind_by_name($stid, ':hdwid', $_COOKIE['HDTWID']);

  $r = @oci_execute($stid);
  if (!$r) {
    print "<font color=red><b>Error on statement execution.</b></font><BR>Please report this error message:<BR>";
    $e = oci_error($stid); print htmlentities($e['message']); print "<BR>";
    include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
    exit;
  }

  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');

$emailmessage="Hello,

This is an important message from Automated Client Eligibility System (ACES). 

Your account password has been reset per your request to the helpdesk.

Please note the following:

--Wait 20 minutes before using the new password

--Do not copy and paste the new password

--Do not reuse a previous password


Your new temporary password is:
     ".$password."

You will be required to change your password upon your next login; 
please do so at your earliest convenience and contact the ACES helpdesk if you have any additional questions.

Thank you.

-The ACES Team
";

mail($_POST['email1'],"[ACES] Password Reset",$emailmessage,"From: \"ACESHelp, Desk\" <Desk.ACESHelp@maine.gov>\n"); 

header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

?><html><head><? include('header.inc'); ?>
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
<h1 align="center">Reset Worker Password</h1>
<div align="center"><? print "<B><font color=green>Worker: ".$worker['FIRST_NAME']." ".$worker['MIDDLE_NAME']." ".$worker['LAST_NAME']." ".$worker['NAME_SUFFIX']." (".$worker['ACCOUNT'].")</center></font></B>";
?>
<P>
<div align="center"><B><font color=green>
<h3>Password was reset successfully.</h3>
<h3>Worker was emailed at <?print $_POST['email1']; ?> with their new password.</h3></font></b></div>
<P><div align="center"><h3><a href="/helpdesk/search/">Click Here To Continue</a></h3></div>
<P><div align="center"><a href="/helpdesk/search/">[Back To Search]</a></div>
<?
include('footer.inc');

} else {

header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

?><html><head><? include('header.inc'); ?>
<script language="JavaScript" type="text/javascript">
<!--
function emailcheck(str) {
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
  if (form.email1.value == "") {  alert( "Please enter the email address." ); form.email1.focus(); return false ; }
  if (form.email2.value == "") {  alert( "You must confirm the email address." ); form.email2.focus(); return false ; }
  if (form.email1.value != form.email2.value) {  alert( "Email addresses do not match." ); form.email2.value=""; form.email2.focus(); return false ; }
  if (emailcheck(form.email1.value)==false) { alert("Email address should be formatted like: first.last@maine.gov"); return false; }
  return true ;
}
//-->
</script>
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
<h1 align="center">Reset Worker Password</h1>
<div align="center"><? print "<B><font color=green>Reset Password For Worker: ".$worker['FIRST_NAME']." ".$worker['MIDDLE_NAME']." ".$worker['LAST_NAME']." ".$worker['NAME_SUFFIX']." (".$worker['ACCOUNT'].")</center></font></B>";
?>
<P>
<? if ($worker['EMAIL']) { ?><div align="center">Confirm that the email address is correct below.<P>
<? } else { ?><div align="center"><font color=red>Enter the worker's email address twice
below to reset their password.</font><P> <? } ?>
<form action=/helpdesk/resetpassword method=post onsubmit="return checkform(this)">
<input type=hidden name="_resetpassword" value="yes">
<? foreach ($worker as $key=>$value) {print "<input type=hidden name=\"worker[$key]\" value=\"".$value."\">\n"; } ?>

<table>
<tr>
 <td>Email Address: </td>
 <td><input type="text" name="email1" size=40 value="<? print ($_POST['email1']?$_POST['email1']:$worker['EMAIL']);?>"> 
     <? if ($_POST['_resetpassword']) {
          if ($_POST['email1']<>$_POST['email2']) {
            print "<B><font color=red>** Email Addresses Must Match **</font></B>"; 
          } else { 
            print "<B><font color=red>** Email Addresses Must Not Empty **</font></B>"; 
          }
        }?></td>
</tr><tr>
 <td>Confirm: </td>
 <td><input type=text name="email2" size=40 value="<? print ($_POST['email2']?$_POST['email2']:$worker['EMAIL']); ?>"></td>
</tr>
<tr><td align=center colspan=2><input type=submit value="Reset Password"></td></tr>
</table></form>
</div>
<P><div align="center"><a href="/helpdesk/search/">[Back To Search]</a></div>
<?
include('footer.inc');
}
?>