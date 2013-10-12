<?php
	require_once 'libs/Smarty.class.php';
	require_once 'includes/StatusService.class.php';
	require_once 'includes/MemberService.class.php';
	require_once 'includes/JournalService.class.php';
	require_once 'includes/PhotoService.class.php';
	require_once 'includes/PayService.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/SqlHelper.class.php';
	require_once 'includes/PhotoComm.class.php';
	require_once 'includes/PhotoType.class.php';
	
	$action=empty($_GET['action'])?'':$_GET['action'];
	session_start();
	
	$smarty=new Smarty();
	$photoService=new PhotoService();
	$common = new Common();
	
	if($action == 'search'){
		$keyword = empty($_POST['keyword'])?'':trim($_POST['keyword']);
		if($keyword == ''){
			$common->message(array('text'=>'搜索条件不能为空！','link'=>'index.php','jump'=>'1'));
			exit;
		}
		
		//查询图片
		$photo_list=$photoService->search_photo($keyword);
		
			//获取用户名
		$username=@$_SESSION['username'];
		
		$count=$common->get_login();
		
			//获取图片类型
		$photoType=new PhotoType();
		$photo_type=$photoType->get_type();
		//分配好友动态
		$smarty->assign('notice_num',$count);
		
		$smarty->assign('username',$username);
		$smarty->assign('photo_list',$photo_list);
		$smarty->assign('photo_type',$photo_type);
		$nav=array("","class='c'","","","");
		$smarty->assign('nav',$nav);

		$smarty->display('photo.html');
		
	}
	if($action == 'albums_photo'){
		//获取相册id
		$albums_id=empty($_GET['albums_id'])?'':$_GET['albums_id'];
		
		$albums_photo=$photoService->get_photo_by_album($albums_id);
		
		$username=@$_SESSION['username'];
		$count=$common->get_login();

		$smarty->assign('notice_num',$count);
		$smarty->assign('username',$username);
		$smarty->assign('albums_photo',$albums_photo);
		$smarty->display('albums_photo.html');
		exit;
	}
	// if($action=='get_free'){
		// //获取用户名
		// $username=@$_SESSION['username'];
// 		
		// $nav=array("","","","class='c'","");
		// $smarty->assign('nav',$nav);
		// $smarty->assign('username',$username);
		// $smarty->display('free.html');
		// exit;
	// }
	if ($action=='buy'){
		$photo_id=empty($_GET['id'])?'':$_GET['id'];
		$member_id=@$_SESSION['member_id'];
		//获取该图片信息
		$photo_info=$photoService->get_photo_info($photo_id);
		
		//获取作者名
		$zuozhe=$photoService->get_photo_info($photo_id);
		$memberService=new MemberService();
		$res=$memberService->get_name_by_id($zuozhe[0]['member_id']);
		$photo_info[0]['username']=$res[0]['member_username'];
		//获取图片评论
		$photoComm = new PhotoComm();
		$comm_list=$photoComm->get_comm($photo_id);

		//获取图片评价
		$eval_list=$photoComm->get_eval($photo_id);
		
		$count=$common->get_login();
		//获取yonghuming
		$username=@$_SESSION['username'];
		
		
		$nav=array("","","class='c'","","");
		$smarty->assign('notice_num',$count);
		$smarty->assign('nav',$nav);	
		$smarty->assign('username',$username);
		$smarty->assign('member_id',$member_id);
		$smarty->assign('comm_list',$comm_list);
		$smarty->assign('eval_list',$eval_list[0]);
		$smarty->assign('photo',$photo_info[0]);
		$smarty->display('buy.html');
		exit;
	}
	//购买成功
	if($action == 'buy_ok'){
		$photo_id=empty($_POST['id'])?'':$_POST['id'];
		$photo_size=empty($_POST['photo_size'])?'':$_POST['photo_size'];
		
		$member_id=@$_SESSION['member_id'];
		if($member_id == ''){
			$common->message(array('text'=>'您尚未登录，请登录后再使用图片购买功能','link'=>'login.php','jump'=>'1'));
			exit;
		}
		
		
		$photo_info=$photoService->get_photo_info($photo_id);
		if($photo_size == '2'){
			$photo_info[0]['photo_price']= round(0.75*$photo_info[0]['photo_price']); 
			$buy_photo=$photo_info[0]['photo_cut1'];
		}else{
			$buy_photo=$photo_info[0]['photo_original'];
		}
		$memberService = new MemberService();
		$member_info=$memberService->get_name_by_id($photo_info[0]['member_id']);
		$member_info_now=$memberService->get_name_by_id($member_id);
		//判断是否为原作者下载
		if($member_id != $member_info[0]['member_id']){

			if($member_info_now[0]['account'] < $photo_info[0]['photo_price']){
				$common->message(array('text'=>'您的金币余额不足，请充值后再次购买','link'=>'recharge.php','jump'=>'1'));
				exit;
			}
			
			$payService=new PayService();	
			$pay_order_no=str_replace("-","",date("Y-m-dH-i-s")).rand(1000,2000);
			$pay_user=$member_id;
			$pay_photo_id=$photo_id;
			$pay_phototype_id=$photo_info[0]['photo_type_id'];
			$pay_money=$photo_info[0]['photo_price'];
			$pay_time=time();
			$pay_ip=$_SERVER["REMOTE_ADDR"];
			
			$sqlHelper=new SqlHelper();
			$account=$photo_info[0]['photo_price'];
			
			
			$oper="account-$account";
			$oper2="account+$account";
			$res = $memberService->change_account($member_id, $member_info[0]['member_id'],$oper,$oper2);
			$res2 = $payService->pay($pay_order_no, $pay_user, $pay_photo_id, $pay_phototype_id, $pay_money, $pay_time, $pay_ip);
			//echo $res."<br/>".$res2;exit;
			if($res =='0' || $res2 =='0'){
				$common->message(array('text'=>'失败，请重试！','link'=>'index.php','jump'=>'1'));
				exit;
	
			}
		}
		//文件下载
		$file = fopen($buy_photo,"r"); // 打开文件
		// 输入文件标签
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".filesize($buy_photo));
		Header("Content-Disposition: attachment; filename=".$buy_photo);
		// 输出文件内容
		echo fread($file,filesize($buy_photo));
		
		fclose($file);
		exit();
	}
	//购买后评价
	if($action == 'evaluate'){
		$photo_id=empty($_GET['photo_id'])?'':$_GET['photo_id'];
		$member_id=@$_SESSION['member_id'];
		if($member_id == ''){
			$common->message(array('text'=>'请登录后再评论此图片！','link'=>'login.php','jump'=>'1'));
			exit;
		}
		
		$payService=new PayService();
		$res=$payService->confirm_pay($member_id, $photo_id);
		
		if($res[0]['n'] == '0'){
			$common->message(array('text'=>'您还未购买该图片，请购买后再评论！','link'=>'index.php','jump'=>'1'));
			exit;
		}
		
		//判断是否已经评价过该图
		$photoComm = new PhotoComm();
		$eval_info=$photoComm->get_comm($photo_id);

		$i=0;
		foreach($eval_info as $k){
			if($k['from_id'] == $member_id){
				$i=1;
			}
		}
		if($i == 1){
			$common->message(array('text'=>'您已经评论过该图片，请勿重复评论！','link'=>'index.php','jump'=>'1'));
			exit;
		}
		//获取用户名
		$username=$_SESSION['username'];
	
		$common=new Common();
		$count=$common->get_login();
		$smarty->assign('notice_num',$count);
		
		$smarty->assign('username',$username);
		$smarty->assign('photo_id',$photo_id);
		$nav=array("","class='c'","","","");
		$smarty->assign('nav',$nav);
		$smarty->display('evaluate.html');
			
		exit;
	}
	if($action == 'evaluate_ok'){
		$member_id=$_SESSION['member_id'];
		$photo_id=empty($_POST['photo_id'])?'':$_POST['photo_id'];
		$evaluate=empty($_POST['grade'])?'':$_POST['grade'];
		$note=empty($_POST['eval_note'])?'':$_POST['eval_note'];
		$dateline=time();
		
		$photoComm = new PhotoComm();
		$res=$photoComm->photo_eval($member_id, $photo_id, $note, $dateline, $evaluate);
		if($res != '1'){
			$common->message(array('text'=>'评价失败，请重试！','link'=>'index.php','jump'=>'1'));
			exit;
		}
		$common->message(array('text'=>'评价成功！','link'=>'index.php','jump'=>'1'));
		exit;
	}
	//获取用户名
	$username=@$_SESSION['username'];
	//获取用户好友动态
	$statusService = new Status();
	$friend_status=$statusService->get_friend_status();
	 // echo "<pre>";
	 // print_r($friend_status);
	 // echo "</pre>";exit;
	$type=empty($_GET['type'])?'':$_GET['type'];
	if($action == 'get_free'){
		if($type == ''){
			$where="photo_price='0'";
		}else{
			$where="photo_price='0' and photo_type_id = '$type'";
		}
	}else{
		//获取全部图片
		
		if($type == ''){
			$where="photo_id>'0'";
		}else{
			$where="photo_type_id = '$type'";
		}
	}
	$photo_list = $photoService->get_type_photo($where);
	
	//获取图片类型
	$photoType=new PhotoType();
	$photo_type=$photoType->get_type();
	
	$count=$common->get_login();
	//分配好友动态
	$smarty->assign('friend_status',$friend_status);
	$smarty->assign('notice_num',$count);
	
	$smarty->assign('username',$username);
	$smarty->assign('photo_list',$photo_list);
	$smarty->assign('photo_type',$photo_type);
	$nav=array("","class='c'","","","");
	$smarty->assign('nav',$nav);

	if($action == 'get_free'){
		$smarty->display('free.html');
		exit;
	}
	$smarty->display('photo.html');
?>