<?php
	require_once 'libs/Smarty.class.php';
	require_once 'includes/MemberService.class.php';
	require_once 'includes/StatusService.class.php';
	require_once 'includes/JournalService.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/FriendService.class.php';
	require_once 'includes/AlbumsService.class.php';
	require_once 'includes/PhotoService.class.php';
	require_once 'includes/MessageService.class.php';
	
	//获取用户id
	$uid=empty($_GET['uid'])?'':$_GET['uid'];
	$smarty=new Smarty();
	session_start();
	$common=new Common();
	
	$member_id=$_SESSION['member_id'];
	
	
	if($member_id == $uid){
		header("Location:user.php");exit;
	}
	//获取用户名
	$username=$_SESSION['username'];
	
	//获取用户基本资料
	$memberService = new MemberService();
	$user_info=$memberService->get_name_by_id($uid);
	$friendService = new FriendService();
	
	$fri_list = $friendService->get_friend($uid);

	//获取好友动态
	$statusService = new Status();
	$limit=5;
	$friend_status=$statusService->get_status($uid, $limit);
	
	//获取好友日志
	$journalService = new JournalService();
	$limit2=10;
	$friend_journal=$journalService->get_journal($uid, $limit2);

	$albumsService=new AlbumsService();
	 $photoService=new PhotoService();
	 $albums_list = $albumsService->get_albums($uid);
	 
	 foreach($albums_list as $k=>$v){
	 	$res=$photoService->get_thum_by_album($v['albums_id']);
		 if(empty($res)){
		 	$albums_list[$k]['album_thum']="images/default.jpg";
		 }else{
		 	$albums_list[$k]['album_thum']=$res['0']['photo_thum'];
		 }
	 }

	//获取好友留言
	$messageService = new MessageService();
	$message_list=$messageService->get_message($uid);

	
	$count=$common->get_login();
	$nav=array("","","class='c'","","");
	//分配好友动态
	$smarty->assign('friend_status',$friend_status);
	$smarty->assign('friend_journal',$friend_journal);
	$smarty->assign('notice_num',$count);
	$smarty->assign('username',$username);
	$smarty->assign('user_info',$user_info['0']);
	$smarty->assign('fri_list',$fri_list);
	//分配相册列表
	$smarty->assign('albums_list',$albums_list);
	//留言
	$smarty->assign('message_list',$message_list);
	$smarty->assign('nav',$nav);
	$smarty->display('space.html');
?>