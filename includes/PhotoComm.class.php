<?php
	/*
	 * 照片评论类
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class PhotoComm{
	 	
		function photo_eval($from_id,$photo_id,$note,$dateline,$evaluate){
			
			//1.将评论存入到数据库中
			$sql1="insert into xk_photo_comment(from_id,photo_id,note,dateline) values('$from_id','$photo_id','$note','$dateline')";
			//echo "$sql1";exit;
			$sqlHelper=new SqlHelper();
			$res1=$sqlHelper->execute_dml($sql1);
			
			//2.查询评论数和评论平均值
			$sql2="select evaluate,eva_num from xk_photo_evaluate where photo_id='$photo_id'";
			
			$res2=$sqlHelper->execute_dql2($sql2);
			
			$evaluate_new=($res2[0]['evaluate']*$res2[0]['eva_num']+$evaluate)/($res2[0]['eva_num']+1);
			$eva_num=$res2[0]['eva_num']+1;
			//将新数据存入到数据库
			$sql3="update xk_photo_evaluate set evaluate='$evaluate_new' , eva_num='$eva_num' where photo_id='$photo_id'";
			//echo $sql3;exit;
			$res3 = $sqlHelper->execute_dml($sql3);
			$sqlHelper->close_connect();
			
			if($res1 == '1' && $res3 == '1'){
				return 1;
			}else{
				return 0;
			}
			
		}

		//获取图片评价
		function get_comm($photo_id){
			$sql="select from_id,note,dateline,member_username from xk_photo_comment,xk_member where photo_id='$photo_id' and from_id=member_id";
			
			$sqlHelper = new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			foreach($res as $k=>$v){
				$res[$k]['dateline']=date("Y-m-d H:i",$v['dateline']);
			}
			
			return $res;
		}
		
		function get_eval($photo_id){
			$sql="select * from xk_photo_evaluate where photo_id='$photo_id'";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
	 }
?>