<?php

class User extends DataObject
{
	private $privilege;
	
	function __construct()
	{
		parent::__construct("users");
	}
	
	function fetchUserEntries()
	{
		$sql = "SELECT id,name,email,Privilege,regDate FROM users ORDER BY privilege LIMIT 10";
		$res = $this->database->queryAndFetch($sql);
		if($this->database->RowCount() > 0)
		{
			return $res;
		}
		return null;
	}
	
	function fetchUserByName($name)
	{
		$sql = "SELECT id,name,email,Privilege,regDate FROM users WHERE name=?";
		$params = array($name);
		$res = $this->database->queryAndFetch($sql, $params);

		if($this->database->RowCount() > 0)
		{
			return $res;
		}
		return null;
	}

	function updateUsersPrivilege($privilege,$id)
	{
		if(is_numeric($privilege) && is_numeric($id))
		{
			$sql = "UPDATE users SET Privilege=? WHERE id=? LIMIT 1";
			$params = array($privilege,$id);
			$this->database->queryAndFetch($sql, $params);
			return true;
		}
		return false;
	}
	function Login($email, $passwd)
	{
		$sql = "SELECT id,name,email,Privilege FROM users WHERE email=? AND password=? LIMIT 1";
		$params = array($email, $passwd);
		$res = $this->database->queryAndFetch($sql, $params);
		if($this->database->RowCount() == 1)
		{
			
			$_SESSION['uid'] = $res[0]->id;
			$_SESSION['username'] = $res[0]->name;
			$_SESSION['email']= $res[0]->email;
			$_SESSION['privilege'] = $res[0]->Privilege; 

			//after successful logon
			//check if remember_me isset
			if(isset($_POST['remember_me']))
			{
				$SECRET_KEY = "!+?";
				setRememberMe($res[0]->name,$SECRET_KEY);
			}
			return true;
		}
		return false;
	}
	
	function getUserPrivilege()
	{
		if(isset($_SESSION['privilege']))
		{
			return $_SESSION['privilege'];
		}
		return -1;
	}

	/*
	 * Set remember me cookie
	 * Everytime the function is called
	 * a new token is generated for the user
	 * and stored in db
	 */
	private function setRememberMe($user,$key)
	{
		// generate a token for storing in cookie
		$token = md5(uniqid($user, true));
		$SECRET_KEY = "!+?";
		$shaToken = hash_hmac('sha256', $token,$SECRET_KEY );
		$oneMonth = time() + (86400 * 30);
		setcookie('rememberme_olacademy', $token, $oneMonth);
	
		$sql = "UPDATE users SET token=? WHERE id=? AND name=? LIMIT 1";
		$params = array($shaToken,$_SESSION['uid'],$user);
		$this->database->ExecuteQuery($sql, $params);
	
	}

	/*
	 * Gets the username and id by the cookies token.
	 * Use sha256 hash and try to find
	 * a match in db.
	 */
	function getUserByCookie()
	{
		$shaToken = hash_hmac('sha256', $_COOKIE['rememberme_olacademy'],"!+?" );
		$sql = 'SELECT id,name FROM users WHERE token=? LIMIT 1';
		$params = array($shaToken);
		$res = $this->database->queryAndFetch($sql,$params);
		if($this->database->RowCount() == 1)
		{
			$_SESSION['uid'] = $res[0]->id;
			$_SESSION['username'] = $res[0]->name;
		}
	}

	function isLoggedIn()
	{
		return (isset($_SESSION['username']));
	}

	function logout()
	{
		session_destroy();
		header("location:" .$_SERVER['PHP_SELF']."");
		setcookie("rememberme_olacademy","" ,time() - (86400 * 31));
	}
	
	function getLoginForm()
	{
		return '<form id="signin" class="navbar-form navbar-right" role="form" method="post">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input id="email" type="email" class="form-control" name="email" value="" placeholder="Användarnamn">
					</div>

					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input id="password" type="password" class="form-control" name="passwd" value="" placeholder="Lösenord">
	                   
					</div>

					<button type="submit" class="btn btn-primary" name="login">Login</button>
	                 <input type="checkbox" name="remember_me" value="remember_me" id="remember_me"/>
				</form>';
	}
}