<?
if ($_COOKIE['HDTUSER'] && $_COOKIE['HDTPASS']) {
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/helpdesk.php');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbconnect.php');
  
  $query = 'select count(*) from worker w, group_assignment g where w.account = :u and w.password = :p and g.worker_id=w.worker_id and g.security_group_id=99';
  $stid = oci_parse($conn, $query);
  oci_bind_by_name($stid, ":u", $_COOKIE['HDTUSER']);
  oci_bind_by_name($stid, ":p", $_COOKIE['HDTPASS']);

  if (!$stid) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $r = oci_execute($stid, OCI_DEFAULT);
  if (!$r) { $e = oci_error($stid); echo htmlentities($e['message']); exit; }
  while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
    if ($row['COUNT(*)']==1) {

  $query2 = 'select worker_id from worker w where w.account = :u';
  $stid2 = oci_parse($conn, $query2);
  oci_bind_by_name($stid2, ":u", $_COOKIE['HDTUSER']);
  if (!$stid2) { $e = oci_error($conn); print htmlentities($e['message']); exit; }
  $r2 = oci_execute($stid2, OCI_DEFAULT);
  if (!$r2) { $e = oci_error($stid2); echo htmlentities($e['message']); exit; }
  while ($row2 = oci_fetch_array($stid2, OCI_RETURN_NULLS+OCI_ASSOC)) {
    setcookie('HDTWID',$row2['WORKER_ID'],time()+1800,"/helpdesk","",1,1);
  }

      include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
      header ("Location: /helpdesk/search/");
      exit; 
    } else { 
      $failure=1; 
    } 
  }
}

if ($_POST['username'] && $_POST['password']) {
  setcookie('HDTUSER',$_POST['username'],time()+1800,"/helpdesk","",1,1);
  setcookie('HDTPASS',htmlentities(base64_encode(pack("H*",md5($_POST['password'])))),time()+1800,"/helpdesk","",1,1);
  header ("Location: /helpdesk/");
  exit;
} 

if ($failure) {print "<div align=center><font color=red><B>Login Failure: Username / Password or Permissions invalid.</B></font></div><P>\n";}

?>
<html><head><? include('header.inc'); ?></head><body>
<div align=center><h1>Helpdesk Administration Tool</h1>
<div align=center><form action="/helpdesk/" method="POST">
<TT>Username: <input type="text" name="username">
<BR>Password: <input type="password" name="password">
<BR><input type="submit" value="Login">
</tt></form>
</body></html>
