<?
####### Free Account Info. ###########
$upvid_login = "";
$upvid_pass = "";
##############################

$not_done=true;
$continue_up=false;
if ($upvid_login & $upvid_pass){
	$_REQUEST['login'] = $upvid_login;
	$_REQUEST['password'] = $upvid_pass;
	$_REQUEST['action'] = "FORM";
	echo "<b><center>Use Default login/pass.</center></b>\n";
}
if ($_REQUEST['action'] == "FORM")
    $continue_up=true;
else{
?>
<table border=0 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action value='FORM' />
<tr><td nowrap>&nbsp;Login*<td>&nbsp;<input type=text name=login value='' style="width:160px;" />&nbsp;</tr>
<tr><td nowrap>&nbsp;Password*<td>&nbsp;<input type=password name=password value='' style="width:160px;" />&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload' /></tr>
</table>
</form>
<?php
}

if ($continue_up)
	{
		$not_done=false;
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=login width=100% align=center>Login to upvid.net</div>
<?php
			$post['login'] = $_REQUEST['login'];
			$post['password'] = $_REQUEST['password'];
			$post['op'] = "login" ;
			$post['redirect'] = "" ;
			$page = geturl("upvid.net", 80, "/login.html", 'http://upvid.net/', 0, $post, 0, $_GET["proxy"], $pauth);
			is_page($page);
			is_notpresent($page, 'HTTP/1.1 302 Moved', 'Error logging in - are your logins correct? First');
			preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
			$cookie = $temp[1];
			$cookies = implode('; ',$cookie);
			$xfss=cut_str($cookies,'xfss=',' ');
			$page = geturl("upvid.net", 80, "/?op=my_files", "http://upvid.net/", $cookies, 0, 0, "");
			is_page($page);
			is_notpresent($page, 'HTTP/1.1 200 OK', 'Error logging in - are your logins correct?Second');
?>
<script>document.getElementById('login').style.display='none';</script>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
	$ref='http://upvid.net/';
	$Url=parse_url($ref);
	$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	$upfrm = cut_str($page,'form-data" action="','"');
	$uid = $i=0; while($i<12){ $i++;}
	$uid += floor(rand() * 10);
	$post['upload_type']="file";
	$post['sess_id']=$xfss;
	$post['ut']="file";
	$post['link_rcpt']="";
	$post['link_pass']='';
	$post['tos']='1';
	$post['submit_btn']=' Upload! ';
	$uurl=$upfrm.$uid.'&js_on=1&utype=reg&upload_type=file';
	$url=parse_url($upfrm.$uid.'&js_on=1&utype=reg&upload_type=file');
?>
<script>document.getElementById('info').style.display='none';</script>
<?

	$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),$ref, 0, $post, $lfile, $lname, "file_0");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
	is_page($upfiles);
	$locat=cut_str($upfiles,"name='fn'>","<");
	unset($post);
	$gpost['fn'] = "$locat" ;
	$gpost['st'] = "OK" ;
	$gpost['op'] = "upload_result" ;
	$Url=parse_url($ref);
	$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $ref, 0, $gpost, 0, $_GET["proxy"],$pauth);
	is_page($page);
	$ddl=cut_str($page,'" class="btitle">','</a></td>');
	$del=cut_str($page,$lname.'.html?killcode=','"');
	//echo $page;
	$download_link=$ddl;
	$delete_link= $ddl.'?killcode='.$del;
	
	}
// Made by Baking 17/09/2009 14:18
?>