<?php
	/*
	 * 这是一个接收用户参数的控制器
	 */
	 require_once 'includes/StatusService.class.php';
	 require_once 'includes/Common.class.php';
	 require_once 'libs/Smarty.class.php';
	 require_once 'includes/MemberService.class.php';
	 require_once 'includes/FriendService.class.php';
	 require_once 'includes/JournalService.class.php';
	 require_once 'includes/MessageService.class.php';
	 
	 $action = empty($_GET['action'])?'':trim($_GET['action']);
	 
	 session_start();
	 //实例化common类
	 $common=new Common();
	 $smarty=new Smarty();
	 if($action == 'sta_post'){
	 	$sta_info=empty($_POST['status'])?'':trim($_POST['status']);
		if($sta_info == ''){
			$common->message(array("text"=>"状态信息不能为空","link"=>"user.php","jump"=>"1"));
			exit;
		}
		$statusService = new Status();
		$res = $statusService->add_status($sta_info,$_SESSION['member_id'],time());
		echo $res;
		if($res!='1'){
			$common->message(array("text"=>"添加失败","link"=>"user.php","jump"=>"1"));
			exit;
		}
		$common->message(array("text"=>"添加状态成功","link"=>"user.php","jump"=>"1"));
		exit;
	 }
	 //添加好友
	 if($action == 'add_fri'){
	 	$fri_id = empty($_GET['uid'])?'':$_GET['uid'];
		//实例化MemberService类
		$memberService=new MemberService();
		$res=$memberService->get_name_by_id($fri_id);
		$nav=array("","","class='c'","","");
		$smarty->assign('fri_id',$fri_id);
		$smarty->assign('nav',$nav);
		$smarty->assign('member_name',$res['0']['member_name']);
		$smarty->display('add_friend.html');
		exit;
	 }
	 if($action == 'add_fri_ok'){
	 	$fri_id = empty($_POST['uid'])?'':$_POST['uid'];
		$con_fri = empty($_POST['con_fri'])?'':$_POST['con_fri'];
		$from_id = $_SESSION['member_id'];
		
		//实例化一个朋友实例
		$friendService=new FriendService();
		$res=$friendService->add_fri($from_id, $fri_id, $con_fri);
		if($res != '1'){
			$common->message(array("text"=>"添加失败，请重试！","link"=>"user.php?action=fri_sea","jump"=>"1"));
			exit;
		}
		$common->message(array("text"=>"添加成功！","link"=>"user.php","jump"=>"1"));
		exit;		 	 	
	 }
	//资料完善
	if($action == 'info_update'){
		$member_id=$_SESSION['member_id'];
		$member_name=empty($_POST['member_name'])?'':trim(addslashes($_POST['member_name']));
		$member_sex=empty($_POST['member_sex'])?'':trim(addslashes($_POST['member_sex']));
		$member_birthday=empty($_POST['member_birthday'])?'':trim(addslashes($_POST['member_birthday']));
		$member_phone=empty($_POST['member_phone'])?'':trim(addslashes($_POST['member_phone']));
		$member_from=empty($_POST['member_from'])?'':trim(addslashes($_POST['member_from']));
		
		$memberService=new MemberService();
		$res=$memberService->update_user($member_id, $member_name, $member_sex, $member_birthday, $member_phone, $member_from);
		if($res != '1'){
			$common->message(array("text"=>"更新个人信息失败，请重试！","link"=>"user.php?action=member_info","jump"=>"1"));
			exit;
		}
		$common->message(array("text"=>"更新个人信息成功！","link"=>"user.php","jump"=>"1"));
		exit;
		
	}
	
	//发表日志
	if($action == 'add_log'){
		$member_id=$_SESSION['member_id'];
		$log_name = empty($_POST['log_name'])?'':addslashes($_POST['log_name']);
		$log_con = empty($_POST['editor1'])?'':addslashes($_POST['editor1']);
		
		if($log_name == '' || $log_con == ''){
			$common->message(array("text"=>"日志标题或内容不能为空！","link"=>"user.php?action=post_log","jump"=>"1"));
			exit;
		}
		
		$journalService=new JournalService();
		$res=$journalService->add_journal($member_id, $log_name, $log_con);
		if($res != '1'){
			$common->message(array("text"=>"日志发表失败，请重试！","link"=>"user.php?action=post_log","jump"=>"1"));
			exit;
		}
		$common->message(array("text"=>"日志发表成功！","link"=>"user.php","jump"=>"1"));
		exit;
	}
	 
	 //上传头像
	 if($action == 'head_update'){
	 	$memberService=new MemberService();

		 $res=$memberService->update_head($_FILES['member_head']);
		 if($res != '1'){
		 	$common->message(array("text"=>"头像上传失败，请重试！","link"=>"user.php?action=my_head","jump"=>"1"));
			exit;
		 }
		 $common->message(array("text"=>"头像上传成功！","link"=>"user.php","jump"=>"1"));
		 exit;
		 
	 }

	 //发表留言
	 if($action == 'message_ok'){
	 	$from_id=$_SESSION['member_id'];
		$to_id=empty($_POST['to_id'])?'':trim($_POST['to_id']);
		$info=empty($_POST['info'])?'':$_POST['info'];
		$dateline=time();
		$messageService = new MessageService();
		
		$res=$messageService->add_message($from_id, $to_id, $info, $dateline);
		if($res != '1'){
			$common->message(array("text"=>"留言失败，请重试！","link"=>"user.php","jump"=>"1"));
		 	exit;
		}
		$common->message(array("text"=>"留言成功！","link"=>"user.php","jump"=>"1"));
		 exit;
	 }
	 //上传图片
	 if($action == 'pic_upload'){
	 	//获取文件的大小
	 	
	 	// echo "<pre>";
		// print_r($_FILES['b_pic1']);
		// echo "</pre>";exit;
		require_once 'includes/PhotoService.class.php';
		require_once 'includes/AlbumsService.class.php';
		//实例化一个对象
		$photoService = new PhotoService();
		
		//判断是否为添加相册
		if(@$_POST['album'] != ''){
			$album = $_POST['album'];
			$albumsService = new AlbumsService();
			$res=$albumsService->add_album($album);
			if($res != '1'){
				$common->message(array("text"=>"相册添加失败，请重试！","link"=>"user.php?action=pic_upload","jump"=>"1"));
				exit;
			}
			$common->message(array("text"=>"相册添加成功！","link"=>"user.php?action=pic_upload","jump"=>"1"));
			exit;
		}
		if(@$_POST['name_pic1'] != ''){
			$name_pic1=empty($_POST['name_pic1'])?'':trim($_POST['name_pic1']);
			$type_pic1=empty($_POST['type_pic1'])?'':trim($_POST['type_pic1']);
			$discrip_pic1=empty($_POST['discrip_pic1'])?'':trim($_POST['discrip_pic1']);
			$biaoq_pic1=empty($_POST['biaoq_pic1'])?'':trim($_POST['biaoq_pic1']);
			$price_pic1=empty($_POST['price_pic1'])?'':trim($_POST['price_pic1']);
			
			$name_pic2=empty($_POST['name_pic2'])?'':trim($_POST['name_pic2']);
			$type_pic2=empty($_POST['type_pic2'])?'':trim($_POST['type_pic2']);
			$discrip_pic2=empty($_POST['discrip_pic2'])?'':trim($_POST['discrip_pic2']);
			$biaoq_pic2=empty($_POST['biaoq_pic2'])?'':trim($_POST['biaoq_pic2']);
			$price_pic2=empty($_POST['price_pic2'])?'':trim($_POST['price_pic2']);
			
			$name_pic3=empty($_POST['name_pic3'])?'':trim($_POST['name_pic3']);
			$type_pic3=empty($_POST['type_pic3'])?'':trim($_POST['type_pic3']);
			$discrip_pic3=empty($_POST['discrip_pic3'])?'':trim($_POST['discrip_pic3']);
			$biaoq_pic3=empty($_POST['biaoq_pic3'])?'':trim($_POST['biaoq_pic3']);
			$price_pic3=empty($_POST['price_pic3'])?'':trim($_POST['price_pic3']);
			
			$name_pic4=empty($_POST['name_pic4'])?'':trim($_POST['name_pic4']);
			$type_pic4=empty($_POST['type_pic4'])?'':trim($_POST['type_pic4']);
			$discrip_pic4=empty($_POST['discrip_pic4'])?'':trim($_POST['discrip_pic4']);
			$biaoq_pic4=empty($_POST['biaoq_pic4'])?'':trim($_POST['biaoq_pic4']);
			$price_pic4=empty($_POST['price_pic4'])?'':trim($_POST['price_pic4']);
			$albums=empty($_POST['albums'])?'':trim($_POST['albums']);
			//echo $albums;exit;
			$res = $photoService->upload_pic($_FILES['b_pic1'],$name_pic1,$type_pic1,$discrip_pic1,$biaoq_pic1,$price_pic1,$albums);
		}
		if(@$_POST['name_pic2'] != ''){
			$res2 = $photoService->upload_pic($_FILES['b_pic2'],$name_pic2,$type_pic2,$discrip_pic2,$biaoq_pic2,$price_pic2,$albums);
		}else{
			$res2 = 1;
		}
		if(@$_POST['name_pic3'] != ''){
			$res3 = $photoService->upload_pic($_FILES['b_pic3'],$name_pic3,$type_pic3,$discrip_pic3,$biaoq_pic3,$price_pic3,$albums);
		}else{
			$res3 = 1;
		}
		if(@$_POST['name_pic4'] != ''){
			$res4 = $photoService->upload_pic($_FILES['b_pic4'],$name_pic4,$type_pic4,$discrip_pic4,$biaoq_pic4,$price_pic4,$albums);
		}else{
			$res4 = 1;
		}
 
		if($res == 1 && $res2 == 1 && $res3 == 1 && $res4 == 1){
			$common->message(array("text"=>"添加图片成功！","link"=>"user.php","jump"=>"1"));
			exit;
		}else{
			$common->message(array("text"=>"添加图片失败，请重试！","link"=>"user.php","jump"=>"1"));
			exit;
		}
	 }
	//获取消息
	if($action == 'notice_friend'){
		$member_id=$_SESSION['member_id'];
		$memberService=new MemberService();
		
		$friendService=new FriendService();
		$do=empty($_GET['do'])?'':$_GET['do'];
		if($do != ''){
			$id=empty($_GET['id'])?'':$_GET['id'];
			if($do == 'yes'){
				
				$fri_id=empty($_GET['member_id'])?'':$_GET['member_id'];
				$member_name=empty($_GET['member_name'])?'':$_GET['member_name'];
				$id=empty($_GET['id'])?'':$_GET['id'];
				$con_fri='';
				$time=time();
				// echo $member_id."<br/>";
				// echo $member_name."<br/>";
				// echo $id."<br/>";exit;
				$res=$friendService->agree_notice($member_id,$fri_id,$member_name,$con_fri,$id,$time);
				if($res != '1'){
					$common->message(array("text"=>"添加好友失败！","link"=>"user.php","jump"=>"1"));
					exit;
				}
				$common->message(array("text"=>"添加好友成功！","link"=>"user.php","jump"=>"1"));
					exit;
			}
		}

		$message=$friendService->fri_notice($member_id);
		
		$res=$memberService->get_name_by_id($message['0']['uid']);
		$fri_name=$res['0']['member_username'];
		
		$message['0']['fri_name']=$fri_name;
		
		$username=empty($_SESSION['username'])?'':$_SESSION['username'];
		$count=$common->get_login();
		$smarty->assign('notice_num',$count);
		$smarty->assign('username',$username);
		$smarty->assign('message',$message['0']);
		$smarty->display("notice_friend.html");
		
	}
?>