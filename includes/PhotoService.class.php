<?php
	/*
	*这是一个图片类
	*封装了所有关于图片的操作
	*
	*/
	
	require_once 'includes/SqlHelper.class.php';
	class PhotoService{
		
		//图片搜索
		function search_photo($keywords){
			$sql="select * from xk_photo where photo_keywords like '%$keywords%' or photo_name like '%$keywords%' or photo_depict like '%$keywords%'";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			foreach($res as $k=>$v){
				$res[$k]['photo_time']=date("Y-m-d H:i",$v['photo_time']);
			}
			return $res;
		}
		
		//删除图片
		function del_photo($photo_id){
			$sql="delete from xk_photo where photo_id='$photo_id'";

			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		//获取所有图片
		function get_type_photo($where){
			$sql="select * from xk_photo where $where order by photo_id DESC";
			//echo $sql;exit;
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			foreach($res as $k=>$v){
				$res[$k]['photo_time']=date("Y-m-d H:i",$v['photo_time']);
			}
			return $res;
		}
		//获得某张图片的信息
		function get_photo_info($id){
			$sql="select * from xk_photo where photo_id='$id'";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			foreach($res as $k=>$v){
				$res[$k]['photo_time']=date("Y-m-d H:i",$v['photo_time']);
			}
			return $res;
			
		}
		
		//根据相册获取缩略图
		function get_thum_by_album($albums_id){
			$sql="select photo_thum from xk_photo where albums_id='$albums_id' limit 0,1";
			//echo $sql;exit;
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		//查找相册照片
		function get_photo_by_album($albums_id){
			$sql="select * from xk_photo where albums_id='$albums_id'";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['photo_time']=date("Y-m-d H:i",$v['photo_time']);
			}
			
			return $res;
		}
		//获取首页推荐图片
		function get_hot_photo(){
			$sql="select * from xk_photo where photo_is_best = '1' limit 0,4";

			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['photo_time']=date("Y-m-d H:i",$v['photo_time']);
			}
			return $res;
		}
		//获取最新图片
		function get_new_photo(){
			$sql="select * from xk_photo order by photo_id DESC limit 0,4";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['photo_time']=date("Y-m-d H:i",$v['photo_time']);
			}
			return $res;
		}
		
		//推荐某个图片
		function change_photo_best($photo_id,$key){
			$sql="update xk_photo set photo_is_best='$key' where photo_id='$photo_id'";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		
		//上传图片,$pic是一个数组
		function upload_pic($pic,$name_pic1,$type_pic1,$discrip_pic1,$biaoq_pic1,$price_pic1,$albums){
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
				$member_id=@$_SESSION['member_id'];
				$b_pic_locaion="/".$member_id.time().rand(1,1000).substr($file_true_name,strrpos($file_true_name,"."));
				$move_to_file=$user_path.$b_pic_locaion; 
				if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))){
					
					//echo $pic['name']."上传ok"." ".$move_to_file;
				}else{
					header("Location:user.php?info=1");
					exit();
				}
			}else{
				header("Location:user.php?info=1");
				exit();
			}
			//自动生成缩略图
			$image=$move_to_file;
			$im=GetImageSize($image);
			$logo='images/logo.png';
			$in=GetImageSize($logo);
			//print_r($im);exit;
			switch($im[2]){
				case 1:
					$im=@ImageCreateFromGIF($image);
					break;
				case 2:
					$im=@ImageCreateFromJPEG($image);
					break;
				case 3:
					$im=@ImageCreateFromPNG($image);
					break;
			}
			//将原图赋值到一个新变量中，后面将会用到
			$old_pic=$im;
			$width = imagesx($im); 
			$height = imagesy($im); 
			//生成小图
			$width2 = intval(3/4*$width);
			$height2 = intval(3/4*$height);
			$newpic = imagecreatetruecolor($width2, $height2); 
			imageCopyreSampled($newpic, $old_pic, 0, 0, 0, 0, $width2, $height2, $width, $height); 
			//输出图像
			//header("Content-type:image/jpeg");
			$new_pic_dir="uploads/".$member_id.time().rand(1,1000).".jpg";
			ImageJpeg($newpic,$new_pic_dir,100);
			
			$in=@ImageCreateFromPNG($logo);
		
			
			//$te=imagecolorallocate($im, 255, 255, 255);
			//$str="hello,world";
		
			//imagettftext($im,12,10,30,30,$te,'arial.ttf',$str);
			
			
			//缩略图
			$newwidth=233;
			$newheight=198;
			$newim = imagecreatetruecolor($newwidth, $newheight); 
			imageCopyreSampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 
			imagecopy($newim,$in,0,80,0,0,'264','50');
			//输出图像
			//header("Content-type:image/jpeg");
			$new_im_dir="uploads/".$member_id.time().rand(1,1000).".jpg";

			ImageJpeg($newim,$new_im_dir,100);
			// Free up memory
			imagedestroy($newim);
			$old_pic_dir="uploads".$b_pic_locaion;
			//echo $b_pic_locaion."<br/>".$new_pic_dir."<br/>".$new_im_dir;exit;
			//将数据插入到数据库
			$member_id=$_SESSION['member_id'];
			$photo_time = time();
			$sql="insert into xk_photo(member_id,albums_id,photo_name,photo_depict,photo_type_id,photo_keywords,photo_thum,photo_original,photo_iscut,photo_cut1,photo_price,photo_time) values('$member_id','$albums','$name_pic1','$discrip_pic1','1','$biaoq_pic1','$new_im_dir','$old_pic_dir','1','$new_pic_dir','$price_pic1','$photo_time')";
			//echo $sql;exit;
			//将图片插入到数据库中
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dml($sql);
			
			//查询图片的id
			$sql2="select photo_id from xk_photo where photo_thum='$new_im_dir'";
			$res2=$sqlHelper->execute_dql2($sql2);
			$photo_id=$res2[0]['photo_id'];
			//插入评价字段
			$dateline=time();
			$sql3="insert into xk_photo_evaluate(member_id,photo_id,evaluate,eva_num,dateline) values('$member_id','$photo_id','0','0','$dateline')";
			$res3=$sqlHelper->execute_dml($sql3);
			$sqlHelper->close_connect();
			if($res == '1' && $res3 == '1'){
				return 1;
			}else{
				return 0;
			}
			
		}
	}
?>