<?php
	/*
	 * 管理日志
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class AdminLog{
	 	
		function add_log($log_info){
			$log_time=time();
			$log_ip=$_SERVER["REMOTE_ADDR"];
			$sql="insert into xk_admin_log(log_time,log_info,log_ip) values('$log_time','$log_info','$log_ip')";
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
			
		}
		
		function get_log(){
			$sql="select * from xk_admin_log order by log_id DESC";
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['log_time']=date("Y-m-d H:i",$v['log_time']);
			}
			return $res;
		}
	 }
?>