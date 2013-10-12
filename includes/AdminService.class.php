<?php
	/*
	 * 管理员类
	 * 
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class AdminService{
	 	
		function check_admin($admin_name,$admin_password){
			//密码比对
			$sql="select admin_password from xk_admin where admin_name='$admin_name'";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			if($res['0']['admin_password'] != md5(md5($admin_password))){
				return 0;
			}else{
				return 1;
			}
		}
	 }
?>