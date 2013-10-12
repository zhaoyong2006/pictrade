<?php
	require_once 'libs/Smarty.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/NewsService.class.php';
	session_start();
	
	$news_id=empty($_GET['id'])?'':$_GET['id'];
	$newsService=new NewsService();
	//获取新闻
	$content=$newsService->get_news_byid($news_id);
	
	//获取消息
	$common=new Common();
	$count=$common->get_login();
	
	$username=@$_SESSION['username'];
	$smarty=new Smarty();

	$smarty->assign('notice_num',$count);
	$smarty->assign('content',$content[0]);

	$smarty->assign('username',$username);
	$smarty->display('content.html');
?>