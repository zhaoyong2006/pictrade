<?php
	//后台
	require_once 'libs/Smarty.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/AdminService.class.php';
	require_once 'includes/NewsService.class.php';
	require_once 'includes/PhotoService.class.php';
	require_once 'includes/PhotoType.class.php';
	require_once 'includes/MemberService.class.php';
	require_once 'includes/AdminLog.class.php';
	
	$smarty=new smarty();
	$smarty->template_dir = array('.' . DS . 'templates/admin' . DS);
	$adminService=new AdminService();
	$newsService=new NewsService();
	$photoService=new PhotoService();
	$photoType=new PhotoType();
	$memberService=new MemberService();
	$adminLog=new AdminLog();
	
	session_start();
	$common=new Common();
	$admin_name=@$_SESSION['admin_name'];

	if($admin_name == ''){
		$smarty->display("login.html");
		exit;
	}
	$action=empty($_GET['action'])?'':$_GET['action'];
	
	//退出
	if($action == 'logout'){
		unset($_SESSION['admin_name']);
		$common->message(array('text'=>'退出成功！','link'=>'admin.php','jump'=>'1'));
		exit;
	}
	//新闻列表
	if($action == 'news'){
		
		$content_list=$newsService->get_all_news();
		
		$smarty->assign("content_list",$content_list);
		$smarty->display("content_list.html");
		exit;
	}
	if($action == 'add_news'){
		
		$mode="insert";
		$content=array();
		
		$smarty->assign("mode",$mode);
		$smarty->assign("content",$content);
		$smarty->display("content_info.html");
		exit;
	}
	if($action == 'content_add'){
		
		$content_title=empty($_POST['content_title'])?'':$_POST['content_title'];
		$content_text=empty($_POST['editor1'])?'':$_POST['editor1'];

		if($content_title == '' || $content_text == ''){
			$common->message(array('text'=>'新闻标题和内容不能为空!','link'=>'admin.php?action=add_news','jump'=>'1'));
			exit;
		}
		$content_time=time();
		$res=$newsService->add_news($content_title, $content_text, $content_time);
		if($res != '1'){
			$common->message(array('text'=>'新闻添加失败!','link'=>'admin.php?action=add_news','jump'=>'1'));
			exit;
		}
		$adminLog->add_log("增加新闻[$content_title]");
		$common->message(array('text'=>'新闻添加成功!','link'=>'admin.php?action=news','jump'=>'1'));
		exit;
	}
	if($action == 'del_news'){
		$news_id=@$_GET['news_id'];
		
		$res=$newsService->del_news($news_id);
		$adminLog->add_log("删除新闻，id为$news_id");
		$common->message(array('text'=>'新闻删除成功!','link'=>'admin.php?action=news','jump'=>'1'));
		exit;
	}
	if($action == 'photo'){
		
		$where="photo_id > '0'";
		$photo_list=$photoService->get_type_photo($where);
		
		$smarty->assign("photo_list",$photo_list);
		$smarty->display("photo_list.html");
		exit;
	}
	//推荐
	if($action == 'photo_best'){
		$photo_id=@$_GET['photo_id'];
		
		$res=$photoService->change_photo_best($photo_id,1);
		$adminLog->add_log("推荐图片，id为$photo_id");
		$common->message(array('text'=>'推荐成功!','link'=>'admin.php?action=photo','jump'=>'1'));
		exit;
	}
	if($action == 'photo_best_off'){
		$photo_id=@$_GET['photo_id'];
		
		$res=$photoService->change_photo_best($photo_id,0);
		$adminLog->add_log("取消推荐图片，id为$photo_id");
		$common->message(array('text'=>'取消推荐成功!','link'=>'admin.php?action=photo','jump'=>'1'));
		exit;
	}
	//删除图片
	if($action == 'del_photo'){
		$photo_id=@$_GET['photo_id'];

		$res=$photoService->del_photo($photo_id);
		$adminLog->add_log("删除图片，id为$photo_id");
		$common->message(array('text'=>'删除成功!','link'=>'admin.php?action=photo','jump'=>'1'));
		exit;
	}
	if($action == 'photo_type'){
		
		$photo_type=$photoType->get_type();
		
		$smarty->assign("photo_type",$photo_type);
		$smarty->display("photo_type.html");
		exit;
	}
	if($action == 'type_add'){
		$type_name=empty($_POST['type_name'])?'':$_POST['type_name'];
		$type_depict=empty($_POST['type_depict'])?'':$_POST['type_depict'];
		if($type_name == ''){
			$common->message(array('text'=>'类型名不能为空!','link'=>'admin.php?action=photo_type','jump'=>'1'));
			exit;
		}
		$res=$photoType->add_type($type_name,$type_depict);
		$adminLog->add_log("添加图片类型[$type_name]");
		$common->message(array('text'=>'添加类型成功!','link'=>'admin.php?action=photo_type','jump'=>'1'));
		exit;
	}
	//会员管理
	if($action == 'member'){
		$member_list=$memberService->get_all_user();
		
		$smarty->assign("member_list",$member_list);
		$smarty->display("member_list.html");
		exit;
	}
	//管理日志
	if($action == 'log_list'){
		$log_list=$adminLog->get_log();
		
		$smarty->assign("log_list",$log_list);
		$smarty->display("log_list.html");
		exit;
	}
	//锁定该用户
	if($action == 'lock_member'){
		$member_id=@$_GET['member_id'];
		$res=$memberService->lock_member($member_id,1);
		$adminLog->add_log("锁定用户，id为$member_id");
		$common->message(array('text'=>'锁定用户成功!','link'=>'admin.php?action=member','jump'=>'1'));
		exit;
	}
	//解锁
	if($action == 'unlock_member'){
		$member_id=@$_GET['member_id'];
		$res=$memberService->lock_member($member_id,0);
		$adminLog->add_log("解锁用户，id为$member_id");
		$common->message(array('text'=>'解除锁定成功!','link'=>'admin.php?action=member','jump'=>'1'));
		exit;
	}
	
	//删除用户
	if($action == 'del_member'){
		$member_id=@$_GET['member_id'];
		$res=$memberService->del_member($member_id);
		$adminLog->add_log("删除用户，id为$member_id");
		$common->message(array('text'=>'删除用户成功!','link'=>'admin.php?action=member','jump'=>'1'));
		exit;
	}
		

	//后台登入默认页，显示服务器相关信息
	$time=date("Y-m-d H:i:s",$_SERVER['REQUEST_TIME']);
	$port=$_SERVER['SERVER_PORT'];
	$server_name=$_SERVER['SERVER_NAME'];
	$server_software=$_SERVER['SERVER_SOFTWARE'];
	$server_type=@PHP_OS;
	$doc_root=$_SERVER['DOCUMENT_ROOT'];
	$max_file=@ini_get('upload_max_filesize');
	
	//将以上数据分配给模板，以便显示

	$smarty->assign("time",$time);
	$smarty->assign("port",$port);
	$smarty->assign("server_name",$server_name);
	$smarty->assign("server_type",$server_type);
	$smarty->assign("server_software",$server_software);
	$smarty->assign("doc_root",$doc_root);
	$smarty->assign("max_file",$max_file);
	
	$smarty->display("start.html");
	exit;
	
?>