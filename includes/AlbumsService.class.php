<?php
	/*
	 * 相册操作类
	 * 
	 */
	 class AlbumsService{
	 	
		private $parmissions = 1;
		
		//添加相册
		function add_album($album){
			$member_id = $_SESSION['member_id'];
			$sql="insert into xk_albums(member_id,albums_name,albums_parmissions) values('$member_id','$album','$this->parmissions')";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;			
		}
		
		function get_albums($member_id){
			
			$sql="select * from xk_albums where member_id = '$member_id'";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			return $res;	
		}
	 }
?>