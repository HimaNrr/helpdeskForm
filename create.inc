<? 
  if (($_GET['cmdNew']<>'Click To Create New Account') && ($_POST['txtNewAccount']=='')) 
    { header("Location: /helpdesk/search/"); exit; }
  if ($_POST['txtNewAccount']=='') {
?>
<html><head><? include('header.inc'); ?>
<script language="JavaScript">
<!-- 
function getXmlHttpRequestObject() {
  if (window.XMLHttpRequest) { return new XMLHttpRequest();
    } else if(window.ActiveXObject) { return new ActiveXObject("Microsoft.XMLHTTP");
    } else { alert("Your Browser Does Not Support Javascript Correctly!");
    }
}
var searchReq = getXmlHttpRequestObject();

function searchSuggest() {
  if (searchReq.readyState == 4 || searchReq.readyState == 0) {
    var str = escape(document.getElementById('txtNewAccount').value);
    searchReq.open("GET", '/helpdesk/search/accountexists?q=' + str, true);
    searchReq.onreadystatechange = handleSearchSuggest; 
    searchReq.send(null);
  }        
}

function handleSearchSuggest() {
  if (searchReq.readyState == 4) {
    var ss = document.getElementById('errormsg')
    if (searchReq.responseText==1) {
      ss.innerHTML = '<b><font color=red>That Account Name Already Exists</font></b>';
      if ( document.getElementById('cmdNew') ) { document.getElementById('cmdNew').style.visibility="hidden"; }
      ss.style.visibility="visible";
    } else if (searchReq.responseText==2) {
      ss.innerHTML = '<b>Account Name Must Be Longer</b>';
      if ( document.getElementById('cmdNew') ) { document.getElementById('cmdNew').style.visibility="hidden"; }
      ss.style.visibility="visible";
    } else if (searchReq.responseText==6) {
      ss.innerHTML = '<b>Account Name Must Be Shorter</b>';
      if ( document.getElementById('cmdNew') ) { document.getElementById('cmdNew').style.visibility="hidden"; }
      ss.style.visibility="visible";
    } else if (searchReq.responseText==3) {
      if ( document.getElementById('cmdNew') ) { document.getElementById('cmdNew').style.visibility="hidden"; }
      ss.style.visibility="hidden";
    } else if (searchReq.responseText==4) {
      ss.innerHTML = '<b>Account Name Contains Invalid Character(s)</b>';
      if ( document.getElementById('cmdNew') ) { document.getElementById('cmdNew').style.visibility="hidden"; }
      ss.style.visibility="visible";
    } else {
      var suggest = '<div><input type="submit" align="right" id="cmdNew" name="cmdNew" value="Click To Create New Account" /></div>';
      ss.innerHTML = suggest;
      if ( document.getElementById('cmdNew') ) { document.getElementById('cmdNew').style.visibility="visible"; }
      ss.style.visibility="visible";
    }
  }
}

--></script>
<style type="text/css" media="screen">
body {font: 11px arial;}
#spanr {text-align: right; color: green;}
#spanl {text-align: left; color: red;}
.suggest_link {background-color: #FFFFFF;padding: 2px 6px 2px 6px;}
#errormsg_title {visibility: hidden; }
.suggest_link_over {background-color: #5588DD;padding: 2px 6px 2px 6px;}
#errormsg {
  position: center;  
  background-color: #FFFFFF; 
  text-align: center; 
  border: 1px solid #000000;
  font-size:12px;
  width:240px; 
  padding:1px 1px 1px 1px;
  visibility: hidden; 
}        

#cmdNew {visibility: hidden;}
</style>
</head>
<body>
<h1 align="center">Worker Account Creation</h1>
<div align="center">Enter <b>new account</b> below:<p>
<form id="frmSearch" action="/helpdesk/search/create?" method="POST">
<input type="text" id="txtNewAccount" name="txtNewAccount" alt="New Account Name" 
        onkeyup="searchSuggest();" autocomplete="off" size=30 align=center value=""/><br>
<P>

<div align="center" id="errormsg"><input type="submit" align="right" id="cmdNew" name="cmdNew" value="Click To Create New Account" />
</div>
</form>
</div>
<P><div align="center"><a href="/helpdesk/search/">[Back To Search]</a></div>
<? include('footer.inc'); ?>
</body>
<? } else { // we've got the new account
  $newaccountname = strtolower($_POST['txtNewAccount']);
  if (strlen($newaccountname)==0) { header("Location: /helpdesk/search/create?cmdNew=Click+To+Create+New+Account"); exit; } 
  if (!preg_match('/^[a-z0-9]+$/i', $newaccountname)) {header("Location: /helpdesk/search/create?cmdNew=Click+To+Create+New+Account"); exit;}
  if (strlen($newaccountname)<4) { header("Location: /helpdesk/search/create?cmdNew=Click+To+Create+New+Account"); exit; } 
  if (strlen($newaccountname)>20) { header("Location: /helpdesk/search/create?cmdNew=Click+To+Create+New+Account"); exit; } 
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/helpdesk.php');
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbconnecttest.php');

  $query = "insert into worker (
    worker_id, dhs_office_id, worker_type_cd, account_status_type_cd, 
    account, last_name, first_name, password, pswd_expire_dt, start_dt, 
    mod_module, mod_user, mod_dt, failed_logins, account_status_dt, BUREAU_TYPE_CD)
  values (
    worker_id.nextval, 99, 'V', 'A',
    :account, ' ', ' ', 'no-login', sysdate-1, sysdate, 
    'HDTool', 0, sysdate, 0, trunc(sysdate),'BFI')";

  $stid = oci_parse($conn, $query);

  oci_bind_by_name($stid, ':account', $newaccountname);

  $r = @oci_execute($stid);
  if (!$r) {
    $e=oci_error($stid);
    $to = 'aces.support@maine.gov';
    $subject = '[ERROR] ACES HELPDESK TOOL';
    $message = 'The ACES HELPDESK TOOL on '.$_SERVER['HTTP_HOST']." failed with the following information:\n\nRequest URI: ".$_SERVER['REQUEST_URI']."\n".__FILE__." @ ".__LINE__."\nError Message :\n".$e['message']."\n".$e['sqltext']."\n".sprintf("\n%".($e['offset']+1)."s", "^")."\n";
    $headers = 'From: aces.support@maine.gov'."\r\n".'Reply-To: aces.support@maine.gov'."\r\n".'X-Mailer: PHP/'.phpversion();
    mail($to, $subject, $message, $headers);
    print "<center><font color=red><b>This account was NOT created succcessfully.<P>We've already been notified of this error.</font><P><A HREF=\"/helpdesk/\">[Return To Search Page]</b></A></center>\n";
//Error on statement execution.</b></font><BR>Please report this error message:<BR>";
//    $e = oci_error($stid); print htmlentities($e['message']); print "<BR>";
    include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
    exit;
  }
  include($_SERVER['DOCUMENT_ROOT'].'/dbinclude/dbclose.php');
  header("Location: /helpdesk/createtoedit?txtSearch=$newaccountname"); 
} // end-else -- we've got a new account. 
?>