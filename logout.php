<?
  // We're clearing the cookies here and into the past.... 
  setcookie('HDTUSER',"-",time()-3600,"/helpdesk","",1,1);
  setcookie('HDTPASS',"-",time()-3600,"/helpdesk","",1,1);
  header ("Location: /helpdesk/");
?>