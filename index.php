<?php
	//这是主页controller，主要是对主页进行显示
	require_once 'libs/Smarty.class.php';
	require_once 'includes/PhotoService.class.php';
	require_once 'includes/NewsService.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/JournalService.class.php';
	require_once 'includes/MemberService.class.php';
	
	session_start();
	$smarty=new Smarty();
	$common=new Common();
	$photoService = new PhotoService();
	$newsService = new NewsService();
	//获取主页推荐图片列表
	$photo_hot = $photoService->get_hot_photo();

	//获取主页最新图片
	$photo_new = $photoService->get_new_photo();
	// echo "<pre>";
	// print_r($photo_new);
	// echo "</pre>";exit;
	//获取站点公告
	$news = $newsService->get_news();
	$username=empty($_SESSION['username'])?'':$_SESSION['username'];
	
	//获取热门日志
	$journalService=new JournalService();
	$hot_jour=$journalService->get_hot_journal('12');
	
	//获取活跃用户
	$memberService=new MemberService();
	$hot_member=$memberService->get_hot_member('12');
	//如果用户已经登录
	//获取消息
	$count=$common->get_login();
	$smarty->assign('notice_num',$count);
	$smarty->assign('username',$username);
	$smarty->assign('photo_hot',$photo_hot);
	$smarty->assign('photo_new',$photo_new);
	$smarty->assign('hot_jour',$hot_jour);
	$smarty->assign('hot_member',$hot_member);
	$smarty->assign('news',$news);
	$smarty->display('index.html');
?>