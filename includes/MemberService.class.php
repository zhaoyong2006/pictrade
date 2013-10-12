<?php
	/*
	 * 这是一个会员操作类
	 * 封装了关于会员的所有方法
	 * 
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class MemberService{
	 	
		//添加会员
		function add_member($username,$passwd,$email){
			$password=md5(md5($passwd));
			$sqlHelper = new SqlHelper();
			$sql = "insert into xk_member (member_username,member_password,member_mail) values('$username','$password','$email')";
			//echo $sql;exit;
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		//锁定用户
		function lock_member($member_id,$key){
			$sql="update xk_member set member_state='$key' where member_id='$member_id'";
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		//删除用户
		function del_member($member_id){
			$sql="delete from xk_member where member_id='$member_id'";
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		//检查用户名是否存在
		function check_member_username($username){
			$sqlHelper = new SqlHelper();
			$sql="select count(*) from xk_member where member_username = '$username'";	
			$res = $sqlHelper->execute_dql2($sql);		
			$sqlHelper->close_connect();
			return $res;
		}
		//检查用户邮箱是否已经使用
		function check_member_mail($email){
			$sqlHelper = new SqlHelper();
			$sql="select count(*) from xk_member where member_mail = '$email'";	
			$res = $sqlHelper->execute_dql2($sql);		
			$sqlHelper->close_connect();
			return $res;
		}
		//验证登录帐号密码
		function check_login($username,$passwd){
			$sqlHelper = new SqlHelper();
			$sql="select member_password,member_state from xk_member where member_username = '$username'";	
			$res = $sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			if($res[0]['member_state'] == '1'){
				return 2;
			}else{
				if($res[0]['member_password'] == md5(md5($passwd))){
					return 1;
				}else{
					return 0;
				}	
			}		
		}
		
		//根据用户名查找用户ID
		function get_userid($username){
			$sqlHelper = new SqlHelper();
			$sql="select member_id from xk_member where member_username='$username'";
			$res = $sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			return $res[0]['member_id'];
		}
		
		//根据ID获取用户姓名
		function get_name_by_id($member_id){
			$sql="select * from xk_member where member_id='$member_id'";
			$sqlHelper=new SqlHelper();
			$res = $sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		//获取站点用户
		function get_all_user(){
			$sql="select * from xk_member order by member_id DESC";
			$sqlHelper = new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		
		//更新用户
		function update_user($member_id,$member_name,$member_sex,$member_birthday,$member_phone,$member_from){
			$sql="update xk_member set member_name='$member_name',member_sex='$member_sex',member_birthday='$member_birthday',member_phone='$member_phone',member_from='$member_from' where member_id='$member_id' ";
			$sqlHelper=new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		
	
		function change_account($member_id,$zuozhe,$account,$oper2){
			$sql="update xk_member set account=$account where member_id='$member_id' ";
			$sql2="update xk_member set account=$oper2 where member_id='$zuozhe' ";
			//echo $sql;exit;
			$sqlHelper=new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$res2 = $sqlHelper->execute_dml($sql2);
			$sqlHelper->close_connect();
			if($res>0 && $res2>0){
				return 1;
			}else{
				return 0;
			}
			
		}
			//充值
		function add_account($member_id, $account){
			$sql="update xk_member set account=$account where member_id='$member_id' ";
			//echo $sql;exit;
			$sqlHelper=new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		
		
		function get_hot_member($limit){
			$sql="select * from xk_member order by account DESC limit 0,$limit";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		//更新头像
		function update_head($pic){
			$file_size=$pic['size'];
	

			//获取文件的类型
			$file_type=$pic['type'];
			//echo $file_type;exit;
			if($file_type!='image/jpg' && $file_type!='image/jpeg'){
				header("Location:user.php?info=2");
				exit();
			}
		
			//判断是否上传ok
			if(is_uploaded_file($pic['tmp_name'])){
				//把文件转存到你希望的目录
				$uploaded_file=$pic['tmp_name'];
				
				//我们给每个用户动态的创建一个文件夹
				$user_path=$_SERVER['DOCUMENT_ROOT']."/uploads";
				
				//$user_path=iconv("utf-8","gb2312",$user_path);
				//判断该用户是否已经有文件夹
				if(!file_exists($user_path)){
					
					mkdir($user_path);
				}
				//$move_to_file=$user_path."/".$_FILES['myfile']['name']; 
				$file_true_name=$pic['name'];
				$member_id=$_SESSION['member_id'];
				$b_pic_locaion="/".$member_id.time().rand(1,1000).substr($file_true_name,strrpos($file_true_name,"."));
				$move_to_file=$user_path.$b_pic_locaion; 
				if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))){
					$member_id=$_SESSION['member_id'];	
					$head_pic="uploads".$b_pic_locaion;
					$sql="update xk_member set member_photo='$head_pic' where member_id='$member_id' ";
					
					$sqlHelper=new SqlHelper();
					$res=$sqlHelper->execute_dml($sql);
					$sqlHelper->close_connect();
					
					return $res;
					//echo $pic['name']."上传ok"." ".$move_to_file;
				}else{
					header("Location:user.php?info=1");
					exit();
				}
			}else{
				header("Location:user.php?info=1");
				exit();
			}
		}
	 }
?>