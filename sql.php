<?php
class sql extends dbconn {	//don't forget it is extended
	public function __construct(){
		$this->initDBO();
	}
	public function saveNotif($msg,$time,$loop,$loop_every,$user){
		$db = $this->dblocal;
		try{
			//here, a prepared statement is used with type NAMED-PLACEHOLDERS
			$stmt = $db->prepare("insert into notif(notif_msg, notif_time, notif_repeat, notif_loop,username) values(:msg , :bctime , :repeat , :loop,:user) ");

			$stmt->bindParam("msg", $msg);
			$stmt->bindParam("bctime", $time);
			$stmt->bindParam("loop", $loop);
			$stmt->bindParam("repeat", $loop_every);
			$stmt->bindParam("user", $user);
			$stmt->execute();
			$stat[0] = true;
			$stat[1] = 'success';
			return $stat;
		}
		catch(PDOException $ex)
		{
			$stat[0] = false;
			$stat[1] = $ex->getMessage();
			return $stat;
		}
	}
	public function updateNotif($id,$nextime) 	//used to update notification table when noticed the user once
	{
		$db = $this->dblocal;
		try
		{
			$stmt = $db->prepare("update notif set notif_time = :nextime, publish_date=CURRENT_TIMESTAMP(),  notif_loop = notif_loop-1 where id=:id ");
			$stmt->bindParam("id", $id);
			$stmt->bindParam("nextime", $nextime);
			$stmt->execute();
			$stat[0] = true;
			$stat[1] = 'Success';	//this is an array which saves if updated correctly
			return $stat;
		}
		catch(PDOException $ex)
		{
			$stat[0] = false;
			$stat[1] = $ex->getMessage();
			return $stat;
		}
	}
	
	public function listNotifUser($user){	//selects only user unseen msgs
		$db = $this->dblocal;
		try
		{	//among many types, FETCH_ASSOC is used 
			$stmt = $db->prepare("SELECT * FROM notif
				WHERE username= :user
				AND notif_loop > 0
				AND notif_time <= CURRENT_TIMESTAMP()");
			$stmt->bindParam("user", $user);
			$stmt->execute();
			$stat[0] = true;
			$stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stat[2] = $stmt->rowCount();
			return $stat;
		}
		catch(PDOException $ex)
		{
			$stat[0] = false;
			$stat[1] = $ex->getMessage();
			return $stat;
		}
	}
	public function getLogin($user,$pass){
		$db = $this->dblocal;
		try
		{
			$stmt = $db->prepare("select * from user where username = :user and password = :pass");
			$stmt->bindParam("user", $user);
			$stmt->bindParam("pass", $pass);
			$stmt->execute();
			$stat[0] = true;
			$stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stat[2] = $stmt->rowCount();
			return $stat;
		}
		catch(PDOException $ex)
		{
			$stat[0] = false;
			$stat[1] = $ex->getMessage();
			return $stat;
		}
	}
	
	public function listUser(){
		$db = $this->dblocal;
		try
		{
			$stmt = $db->prepare("select * from user");
			$stmt->execute();
			$stat[0] = true;
			$stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stat[2] = $stmt->rowCount();
			return $stat;
		}
		catch(PDOException $ex)
		{
			$stat[0] = false;
			$stat[1] = $ex->getMessage();
			return $stat;
		}
	}
	public function listNotif(){	//used to gather all the notifications
		$db = $this->dblocal;
		try
		{
			$stmt = $db->prepare("select * from notif");
			$stmt->execute();
			$stat[0] = true;
			$stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stat[2] = $stmt->rowCount();
			return $stat;
		}
		catch(PDOException $ex)
		{
			$stat[0] = false;
			$stat[1] = $ex->getMessage();
			return $stat;
		}
	}
}