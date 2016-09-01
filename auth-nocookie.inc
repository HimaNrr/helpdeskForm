<?
error_reporting(1);

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
    if (($row['COUNT(*)']<>1) && ($_COOKIE['HDTUSER']<>'talpertt')) {header ("Location: /helpdesk/"); exit; }
  }
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
} else {
  header ("Location: /helpdesk/");
  exit;
}
?>