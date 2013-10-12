<?php
	/*
	 * 这是关于用户相关的控制器
	 * 
	 */
	require_once 'libs/Smarty.class.php';
	require_once 'includes/MemberService.class.php';
	require_once 'includes/StatusService.class.php';
	require_once 'includes/JournalService.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/FriendService.class.php';
	require_once 'includes/AlbumsService.class.php';
	require_once 'includes/PhotoService.class.php';
	require_once 'includes/MessageService.class.php';
	require_once 'includes/PayService.class.php';
	
	$action=empty($_GET['action'])?'':trim($_GET['action']);
	$smarty=new Smarty();
	session_start();
	$common=new Common();
	$friendService = new FriendService();
	//会员注册
	if($action == 'register_ok'){
		//获取前台提交的数据
		$member_username=empty($_POST['member_username'])?'':trim(addslashes($_POST['member_username']));
		$member_password=empty($_POST['member_password'])?'':trim($_POST['member_password']);
		$member_password_confirm=empty($_POST['member_password_confirm'])?'':trim(addslashes($_POST['member_password_confirm']));
		$member_mail=empty($_POST['member_mail'])?'':trim($_POST['member_mail']);
		$authcode=empty($_POST['authcode'])?'':addslashes(trim(strtolower($_POST['authcode'])));
		if(empty($authcode)){
	
			$common->message(array('text'=>'验证码不能为空!','link'=>'reg.php','jump'=>'1'));
			exit;
		}
		if($authcode!=@$_SESSION['authcode']){
			$_SESSION['authcode']=false;
			unset($_SESSION['authcode']);
			$common->message(array('text'=>'验证码错误!','link'=>'reg.php','jump'=>'1'));
			exit;
		}
		if($member_password!=$member_password_confirm){
			$common->message(array('text'=>'两次密码不一致!','link'=>'reg.php','jump'=>'1'));
			exit;
		}
		$memberService = new MemberService();
		$res = $memberService->add_member($member_username, $member_password, $member_mail); 

		if($res != '1'){
			$common->message(array('text'=>'注册失败!','link'=>'reg.php','jump'=>'1'));
			exit;
		}
		
		$_SESSION['username']=$member_username;
		$member_id=$memberService->get_userid($member_username);
		$_SESSION['member_id']=$member_id;
		$common->message(array('text'=>'注册成功!','link'=>'user.php','jump'=>'1'));
		exit;
	}
	//AJAX检查用户名
	if($action=='check_member_username'){
		$member_username=empty($_GET['member_username'])?'':trim($_GET['member_username']);
		$memberService = new MemberService();
		$count = $memberService->check_member_username($member_username);
		if($count[0]['count(*)']>0){
			echo('1');
		}else{
			echo('0');
		}
		exit;
	}
	//AJAX检查会员邮件地址
	if($action=='check_member_mail'){
		$member_mail=empty($_GET['member_mail'])?'':trim($_GET['member_mail']);
		// if(!is_email($member_mail)){
			// echo('1');
			// exit;
		// }
		$memberService = new MemberService();
		$count = $memberService->check_member_username($member_mail);
		if($count[0]['count(*)']>0){
			echo('1');
		}else{
			echo('0');
		}
		exit;
	}
	//处理登陆
	if($action=='login_ok'){
		$member_username=empty($_POST['member_username'])?'':trim(addslashes($_POST['member_username']));
		$member_password=empty($_POST['member_password'])?'':trim($_POST['member_password']);
		if($member_username == ''){
			$common->message(array('text'=>'帐号不能为空!','link'=>'login.php','jump'=>'1'));
			exit;
		}
		if($member_password == ''){
			$common->message(array('text'=>'密码不能为空!','link'=>'login.php','jump'=>'1'));
			exit;
		}
		$memberService=new MemberService();
		$res=$memberService->check_login($member_username,$member_password);
		if($res == '2'){
			$common->message(array('text'=>'该用户已被锁定，请联系管理员，解锁后才能登录!','link'=>'login.php','jump'=>'1'));
			exit;
		}
		if($res != 1){
			$common->message(array('text'=>'用户帐号或密码错误!','link'=>'login.php','jump'=>'1'));
			exit;
		}

		$_SESSION['username']=$member_username;
		$member_id=$memberService->get_userid($member_username);
		$_SESSION['member_id']=$member_id;
		$common->message(array('text'=>'登录成功!','link'=>'user.php','jump'=>'1'));
		exit;
	}

	//处理退出
	if($action == 'logout'){
		unset($_SESSION['member_id'],$_SESSION['username']);
		$common->message(array('text'=>'退出成功!','link'=>'index.php','jump'=>'1'));
		exit;
	}
	if($action=='post_log'){
		$nav=array("","","class='c'","","");
		$smarty->assign('nav',$nav);
		$smarty->display('post_log.html');
		exit;
	}
	//状态心情列表
	if($action=='mode_list'){
		$statusService=new Status();
		$member_id=$_SESSION['member_id'];
		
		$mood_list=$statusService->get_status($member_id, '10');
		$nav=array("","","class='c'","","");
		$smarty->assign('nav',$nav);
		$smarty->assign('mood_list',$mood_list);
		$smarty->display('mood_list.html');
		exit;
	}
	
	//日志列表
	if($action=='journal_list'){
		$journalService=new JournalService();
		$member_id=$_SESSION['member_id'];
		
		$journal_list=$journalService->get_journal($member_id, '10');
		//获取用户名
		$username=$_SESSION['username'];
		$count=$common->get_login();
		$nav=array("","","class='c'","","");
		$smarty->assign('username',$username);
		$smarty->assign('notice_num',$count);
		$smarty->assign('nav',$nav);
		$smarty->assign('journal_list',$journal_list);
		$smarty->display('post_list.html');
		exit;
	}
	
	//好友查找
	if($action=='fri_sea'){
		$username = $_SESSION['username'];
		//获取站点用户
		$memberService = new MemberService();
		$mem_list=$memberService->get_all_user();		
		$count=$common->get_login();

		$smarty->assign('notice_num',$count);
		$smarty->assign('username',$username);
		$smarty->assign('mem_list',$mem_list);
		$smarty->display('fri_sea.html');
		exit;
	}
	
	//个人资料
	if($action == 'myinfo'){
		$member_id = $_SESSION['member_id'];
		$username = $_SESSION['username'];
		
		$memberService = new MemberService();
		$res=$memberService->get_name_by_id($member_id);
		$count=$common->get_login();

		$smarty->assign('notice_num',$count);
		$smarty->assign('username',$username);
		$smarty->assign('member',$res['0']);
		$smarty->display('member_info.html');
		exit;
	}
	
	//我的头像
	if($action == 'my_head'){
		$member_id = $_SESSION['member_id'];
		$username = $_SESSION['username'];
		

		$count=$common->get_login();

		$nav=array("","","class='c'","","");
		$smarty->assign('nav',$nav);
		$smarty->assign('notice_num',$count);
		$smarty->assign('username',$username);

		$smarty->display('member_head.html');
		exit;
	}
	//上传图片
	if($action == 'pic_upload'){
		$member_id = $_SESSION['member_id'];
		$username=$_SESSION['username'];
		
		//获取个人相册
		require_once 'includes/AlbumsService.class.php';
		
		$albumsService = new AlbumsService();
		$albums=$albumsService->get_albums($member_id);

		//获取图片分类
		require_once 'includes/PhotoType.class.php';
		$photoType=new PhotoType();
		$photo_type=$photoType->get_type();
		
		$count=$common->get_login();
		$nav=array("","","class='c'","","");
		$smarty->assign('username',$username);
		$smarty->assign('notice_num',$count);
		$smarty->assign('albums',$albums);
		$smarty->assign('photo_type',$photo_type);
		$smarty->assign('nav',$nav);

		$smarty->display('pic_upload.html');
		exit;
	}
	//首先判断用户是否登录
	if(@$_SESSION['member_id'] == ''){
		$common->message(array('text'=>'您尚未登录，请登录后再使用个人主页功能','link'=>'login.php','jump'=>'1'));
		exit;
	}
	
	$member_id=$_SESSION['member_id'];
	//获取用户名
	$username=$_SESSION['username'];
	
	//获取用户基本资料
	$memberService = new MemberService();
	$user_info=$memberService->get_name_by_id($member_id);
	
	$fri_list = $friendService->get_friend($member_id);

	//获取用户好友动态
	$statusService = new Status();
	$friend_status=$statusService->get_friend_status();
	 // echo "<pre>";
	 // print_r($friend_status);
	 // echo "</pre>";exit;
	 //获取相册列表
	 $albumsService=new AlbumsService();
	 $photoService=new PhotoService();
	 $albums_list = $albumsService->get_albums($member_id);

	 
	 foreach($albums_list as $k=>$v){
	 	
	 	$res=$photoService->get_thum_by_album($v['albums_id']);

		 if(empty($res)){
		 	$albums_list[$k]['album_thum']="images/default.jpg";
		 }else{
		 	$albums_list[$k]['album_thum']=$res['0']['photo_thum'];
		 }
	 }	// echo "<pre>";
	// print_r($albums_list);
	// echo "</pre>";exit;
	//获取留言
	$messageService = new MessageService();
	$message_list=$messageService->get_message($member_id);
	
	//获取收益
	$payService=new PayService();
	$pay_log=$payService->get_member_pay($member_id,'10');
	$count=$common->get_login();
	$nav=array("","","class='c'","","");
	//分配好友动态
	$smarty->assign('friend_status',$friend_status);
	//分配相册列表
	$smarty->assign('albums_list',$albums_list);
	
	$smarty->assign('notice_num',$count);
	$smarty->assign('username',$username);
	$smarty->assign('user_info',$user_info['0']);
	$smarty->assign('fri_list',$fri_list);
	//留言
	$smarty->assign('message_list',$message_list);
	//收益
	$smarty->assign('pay_log',$pay_log);
	$smarty->assign('nav',$nav);
	$smarty->display('user.html');
?>