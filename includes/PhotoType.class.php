<?php
	/*
	 * 图片类型
	 */
	 require_once 'includes/Sqlhelper.class.php';
	 class PhotoType{
	 	
		function get_type(){
			$sql="select * from xk_photo_type";
			
			$sqlHelper=new SqlHelper();
			$photo_type=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			return $photo_type;
		}
		
		function add_type($type_name,$type_depict){
			$sql="insert into xk_photo_type(type_name,type_depict) values('$type_name','$type_depict')";
			
			$sqlHelper=new SqlHelper();
			$photo_type=$sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			
			return $photo_type;
		}
	 }
?>