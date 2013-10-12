<?php
	/*
	 * 支付表类
	 * 
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class PayService{
	 	
		function pay($pay_order_no,$pay_user,$pay_photo_id,$pay_phototype_id,$pay_money,$pay_time,$pay_ip){
			$sql="insert into xk_pay(pay_order_no,pay_user,pay_photo_id,pay_phototype_id,pay_money,pay_time,pay_ip) values('$pay_order_no','$pay_user','$pay_photo_id','$pay_phototype_id','$pay_money','$pay_time','$pay_ip')";
			
			$sqlHelper =new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		//确认角色是否已购买
		function confirm_pay($pay_user,$pay_photo_id){
			$sql="select count(*) as n from xk_pay where pay_user='$pay_user' and pay_photo_id='$pay_photo_id'";
			//echo $sql;exit;
			$sqlHelper =new SqlHelper();
			$res = $sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		function get_member_pay($member_id,$limit){
			$sql="select pay_user,member_username,pay_money,pay_photo_id,photo_name from xk_member as m,xk_pay as p,xk_photo as o where p.pay_photo_id=o.photo_id and o.member_id='$member_id' and p.pay_user=m.member_id order by pay_id DESC limit 0,$limit";
			//echo $sql;exit;
			$sqlHelper =new SqlHelper();
			$res = $sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
	 }
?>