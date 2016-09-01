<? 
  include('auth-nocookie.inc');
  $parameter = substr($_SERVER['REQUEST_URI'],strrpos($_SERVER['REQUEST_URI'],'/')+1,strlen($_SERVER['REQUEST_URI']));
  $parameter = substr($parameter,0,strpos($parameter,'?'));
  if (($parameter == '') or ($parameter == 'search')) { 

    header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
    header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
    header( "Cache-Control: no-cache, must-revalidate" );
    header( "Pragma: no-cache" );

?><html><head><? include('header.inc'); ?>
<script language="JavaScript">
<!-- 
function getXmlHttpRequestObject() {
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    } else if(window.ActiveXObject) {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        alert("Your Browser Does Not Support Javascript Correctly!");
    }
}
//Our XmlHttpRequest object to get the auto suggest
var searchReq = getXmlHttpRequestObject();

//Called from keyup on the search textbox.
//Starts the AJAX request.
function searchSuggest() {
//    document.getElementById('txtCreate').value=document.getElementById('txtSearch').value;
  if ( document.getElementById('error0') ) { document.getElementById('error0').style.visibility="hidden"; }
  if ( document.getElementById('error1') ) { document.getElementById('error1').style.visibility="hidden"; }
  if ( document.getElementById('error3') ) { document.getElementById('error3').style.visibility="hidden"; }
  if ( document.getElementById('error4') ) { document.getElementById('error4').style.visibility="hidden"; }
  if (searchReq.readyState == 4 || searchReq.readyState == 0) {
    var str = escape(document.getElementById('txtSearch').value);
    if (str.length>2) { document.getElementById('cmdNew').style.visibility="visible";  } else { document.getElementById('cmdNew').style.visibility="hidden"; }
    searchReq.open("GET", 'search/find?q=' + str, true);
    searchReq.onreadystatechange = handleSearchSuggest; 
    searchReq.send(null);
  }        
}

function searchSuggestOnLoad() {
  if (searchReq.readyState == 4 || searchReq.readyState == 0) {
    var str = escape(document.getElementById('txtSearch').value);
    if (str.length>2) { document.getElementById('cmdNew').style.visibility="visible";  } else { document.getElementById('cmdNew').style.visibility="hidden"; }
    searchReq.open("GET", 'search/find?q=' + str, true);
    searchReq.onreadystatechange = handleSearchSuggest; 
    searchReq.send(null);
  }        
}

//Called when the AJAX response is returned.
function handleSearchSuggest() {
  if (searchReq.readyState == 4) {
    var ss = document.getElementById('search_suggest')
    ss.innerHTML = '';
    var str = searchReq.responseText.split("\n");
    if (str.length<3) { 
      ss.style.visibility="hidden";  
      document.getElementById('search_suggest_title').style.visibility="hidden";
    } else {
      ss.style.visibility="visible";  
      document.getElementById('search_suggest_title').style.visibility="visible";
      document.getElementById('cmdNew').style.visibility="visible";
      for(i=0; i < str.length - 2; i++) {
        //Build our element string.  This is cleaner using the DOM, but
        //IE doesn't support dynamically added attributes.
        var suggest = '<div onmouseover="javascript:suggestOver(this);" ';
        suggest += 'onmouseout="javascript:suggestOut(this);" ';
        suggest += 'onclick="javascript:setSearch(this.innerHTML);" ';
        suggest += 'class="suggest_link" align="left">' + str[i] + '</div>';
        ss.innerHTML += suggest;
      }
    }
  }
}

function suggestOver(div_value) { div_value.className = 'suggest_link_over'; }
 
function suggestOut(div_value) { div_value.className = 'suggest_link'; }

//Click function
function setSearch(value) {
  document.getElementById('txtSearch').value = value;
  document.getElementById('search_suggest_title').style.visibility="hidden";
  document.getElementById('search_suggest').style.visibility="hidden";
  document.getElementById('frmSearch').submit();
}

--></script>
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
        background-color: #88BBFF;
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
<body onLoad="searchSuggestOnLoad();">
<h1 align="center">Worker Search</h1>
<div align="center">Type in the worker's name, or account to search.<p>
<? if ($_GET['error']=='0') {print "<div id=\"error0\"><b>Invalid Information Entered, Please Try Again.</b></div>\n";} ?>
<? if ($_GET['error']=='1') {print "<div id=\"error1\"><b>No Accounts Found, Please Try Again.</b></div>\n";} ?>
<? if ($_GET['error']=='3') {print "<div id=\"error3\"><b>Multiple Accounts Found, Please Try Again.</b></div>\n";} ?>
<? if ($_GET['error']=='4') {print "<div id=\"error3\"><b>Account Corrupt, Please Fix Account.</b></div>\n";} ?>

<form id="frmSearch" action="/helpdesk/choose">
    <input type="text" id="txtSearch" name="txtSearch" alt="Search Criteria" 
        onkeyup="searchSuggest();" autocomplete="off" size=30 align=center value="<? print $_GET['txtSearch'];
?>"/><br>

<div id="search_suggest_title"><p>Select the account you wish to see below:</div>
<div align="center" id="search_suggest"><span id="spanl"></span><span
id="spanr" align="right"></span></div>
</form>
<div align="center" id=cmdNew><a href="/helpdesk/search/create?cmdNew=Click+To+Create+New+Account">[Create New Account]</a></div>
</div>
<? include('footer.inc'); ?>
</body>
</html>
<?exit; 
} else {
// LEGACY //   if ($parameter == 'display')       { include "display.inc"; }
  if ($parameter == 'find')          { include "find.inc"; }
  if ($parameter == 'accountexists') { include "accountexists.inc"; }
  if ($parameter == 'create')        { include "create.inc"; }
}
?>