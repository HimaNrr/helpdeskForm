<?
include('auth.inc');
include('stripslashes.inc');
include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/helpdesk.php');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbconnect.php');
$worker=$_POST['worker'];

$stid = oci_parse($conn, "select wc.note, wc.custom_note \"CUSTOM_NOTE\",
 to_char(wc.date_added,'Month dd, yyyy hh12:mi:ss am') \"DATE_ADDED\", 
 w.account \"HELPDESK\" 
 from worker_comment wc, worker w
 where wc.worker_id=:wid
 and w.worker_id=wc.worker_id_helpdesk
 order by wc.date_added asc ");

oci_bind_by_name($stid,"wid",$worker['WORKER_ID']);
if (!$stid) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
$r = oci_execute($stid, OCI_DEFAULT);
if (!$r) { $e = oci_error($stid); echo htmlentities($e['message']); exit; }


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
<h1 align="center">Account Comments</h1>
<P>
<div align="center">
<?
$tableheaderprint=0;
while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {

  if ($tableheaderprint==0) { 
     print "<table border=1><tr><th>Date</th><th>Helpdesk<BR>User</th><th>System Note</th><th>Admin Note</th></tr>"; 
     $tableheaderprint=1;
  }

  print "<TR><TD>".$row['DATE_ADDED']."</TD><TD>".$row['HELPDESK']."</TD><TD>".$row['NOTE']."</TD><TD>".$row['CUSTOM_NOTE']."</TD></TR>";
}

if ($tableheaderprint==1) { 
   print "</table>"; 
} else {
   print "<h1><font color=orange><b>No Comments Found.</b></font></h1>"; 
}

?>

<P><div align="center">
<form action=/helpdesk/displaycomments method=post>

<? 

foreach ($worker as $key=>$value) {print "<input type=hidden name=\"worker[$key]\" value=\"".$value."\">\n"; } 

?>
<?

 print "<td colspan=2><input name=txtComment type=text  size=150>";
 print "</td>";

if ($_POST['txtComment']) {
$admincomment="";
$admincomment=$_POST['txtComment'];
if (trim($admincomment)<>""){
$query = "insert into worker_comment (worker_id,note,custom_note, worker_id_helpdesk) values (:wid,:note,:adminnote,:hdwid)";
      $stid = oci_parse($conn, $query);
      $note = "Admin Note Added by ".$worker['FIRST_NAME']." ".$worker['LAST_NAME'];
      oci_bind_by_name($stid, ':wid', $worker['WORKER_ID']);
      oci_bind_by_name($stid, ':note', $note);
      oci_bind_by_name($stid, ':adminnote',$admincomment);
      oci_bind_by_name($stid, ':hdwid', $_COOKIE['HDTWID']);
      $r = @oci_execute($stid);

}}
$admincomment="";
?>

<br />
<input type=submit value="Add Comment">
<br />
<i>new comments will be visible after refresh!</i>
</form>
</div>
<P><div align="center">
<a href="/helpdesk/search/">[Back To Search]</a></div>
<?
include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
include('footer.inc');
?>
