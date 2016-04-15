<?php

class UserObject extends DataObject
{

	function __construct()
	{
		parent::__construct("users");
	}

	function fetchUserEntries()
	{
		$sql = "SELECT id,name,email,Privilege,regDate FROM users ORDER BY privilege LIMIT 10";
		$res = $this->database->queryAndFetch($sql);
		if ($this->rowCount() > 0)
		{
			return $res;
		}
		return null;
	}

	function fetchUserByName($name)
	{
		$condition = array('name' => $name);
		$values = array('id', 'name','lastname', 'email', 'Privilege', 'regDate'); // never select password.

		$res = parent::fetchSingleEntryByValue($condition, $values);

		if ($res != null)
		{
			return $res;
		}
		return null;
	}

	function updateUsersPrivilege($privilege, $id)
	{
		if (is_numeric($privilege) && is_numeric($id))
		{
			$values = array('Privilege' => $privilege);
			$condition = array('id' => $id);
			parent::editSingleEntry($values, $condition);

			return true;
		}
		return false;
	}

	function login($email, $password)
	{
		$condition = array('email' => $email);
		$res = parent::fetchSingleEntryByValue($condition);

		if ($this->rowCount() == 1)
		{
			if (password_verify($password, $res->password)) // requires PHP 5.4
			{
				$sql = "SELECT id,name,email,Privilege, lastname, changePassword FROM users WHERE email=? LIMIT 1";
				$params = array($email); // No duplicates of email
				$res = $this->database->queryAndFetch($sql, $params);

				if ($this->rowCount() == 1)
				{
				    self::populateSession($res[0]);
					if (isset($_POST["remember_me"]))
					{
						$SECRET_KEY = "!+?";
						$this->setRememberMe($res[0]->name, $SECRET_KEY);
					}
				}

				return true;
			}
		}

		return false;
	}

	public function getUserPrivilege()
	{
		if (isset($_SESSION["privilege"]))
		{
			return $_SESSION["privilege"];
		}
		return - 1;
	}

	public function isAdmin()
	{
	    return self::getUserPrivilege() === "2";
	}

	
	private function populateSession($res)
	{
	    $_SESSION["uid"] = $res->id;
	    $_SESSION["username"] = $res->name;
	    $_SESSION["lastname"] = $res->lastname;
	    $_SESSION["email"] = $res->email;
	    $_SESSION["privilege"] = $res->Privilege;
	    $_SESSION["changePassword"] = $res->changePassword;
	}

	/*
	 * Set remember me cookie
	 * Everytime the function is called
	 * a new token is generated for the user
	 * and stored in db
	 */
	private function setRememberMe($user, $key)
	{
		// generate a token for storing in cookie
		$token = md5(uniqid($user, true));
		$SECRET_KEY = "!+?";
		$shaToken = hash_hmac('sha256', $token, $SECRET_KEY);
		$oneMonth = time() + (86400 * 30);
		setcookie('rememberme_olacademy', $token, $oneMonth);

		$sql = "UPDATE users SET token=? WHERE id=? AND name=? LIMIT 1";
		$params = array($shaToken, $_SESSION["uid"], $user);
		$this->database->ExecuteQuery($sql, $params);
	}

	/*
	 * Gets the username and id by the cookies token.
	 * Use sha256 hash and try to find
	 * a match in db.
	 */
	function getUserByCookie()
	{
		$shaToken = hash_hmac('sha256', $_COOKIE["rememberme_olacademy"], "!+?");
		$sql = "SELECT id,name,email,Privilege, lastname, changePassword FROM users WHERE token=? LIMIT 1";
		$params = array($shaToken);
		$res = $this->database->queryAndFetch($sql, $params);
		if ($this->rowCount() == 1)
		{
		    self::populateSession($res[0]);
		}
	}

	function isLoggedIn()
	{
		return (isset($_SESSION["username"]));
	}

	function logout()
	{
		session_destroy();
		header("location:" . $_SERVER["PHP_SELF"] . "");
		setcookie("rememberme_olacademy", "", time() - (86400 * 31));
	}

	function getLoginForm()
	{
		$old = '<form id="signin" class="navbar-form navbar-right" method="post">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input id="email" type="email" class="form-control" name="email" value="" placeholder="Användarnamn">
					</div>

					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input id="password" type="password" class="form-control" name="passwd" value="" placeholder="Lösenord">

					</div>

					<button type="submit" class="btn btn-primary" id="btn_login" name="login">Login</button>
					<button type="submit" class="btn btn-primary" name="Registera">Registera</button>
	                 <input type="checkbox" name="remember_me" value="remember_me" id="remember_me"/>
				</form>';
		
		return '<form class="loginForm" method="post">
						<div class="loginGroup">
							<span class="inputGroup">
								<span class="loginIcon"><img src="img/user.png" alt="user" /></span><input id="email" type="email" class="loginField" name="email" value="" placeholder="Användarnamn">
							</span>
							
							<span class="inputGroup">
								<span class="loginIcon"><img src="img/lock.png" alt="pwd" /></span><input id="password" type="password" class="loginField" name="passwd" value="" placeholder="Lösenord">
							</span>
						</div>
						
						<div class="loginGroup">
							
							
							<span class="inputGroup">
								<button type="submit" class="btn btn-primary" id="btn_login" name="login">Login</button>
								<button type="submit" class="btn btn-primary" name="Registera">Registera</button>
							<span class="inputGroup">
							
							<span class="inputGroup">
								<input type="checkbox" name="remember_me" value="remember_me" id="remember_me"/>
								<span style="font-size:13px;">Kom ihåg mig</span>
							<span class="inputGroup">
							
						</div>
					</form>';
	}

	public function updateUser($name, $email, $lastname, $newPassword)
	{
		$id = $_SESSION["uid"];
		$values = array('name' => $name, 'email' => $email, 'lastname' => $lastname);
		$condition = array('id' => $id);

		if ($newPassword != "")
		{
			$password = password_hash($newPassword, PASSWORD_BCRYPT, array('cost' => 12));
			$values["password"] = $password;
			$values["changePassword"] = 0;
		}

		parent::editSingleEntry($values, $condition);
		$res = parent::fetchSingleEntryByValue($condition);
		self::populateSession($res);
		populateInfo("Updaterad!");

		header("location: " .$_SERVER["PHP_SELF"] . "?m=1");
	}

	public function forgottenPassword($email)
	{
		// validate email
		$condition = array('email' => $email);
		$values = array('id');
		$res = parent::fetchSingleEntryByValue($condition, $values);

		if ($res != null)
		{
			// generate a new password.
			$plainTextPassword = $this->generateRandomPassword();
			$password = password_hash($plainTextPassword, PASSWORD_BCRYPT, array('cost' => 12));

			// set password to database
			$values = array('password' => $password, "changePassword" => 1);
			if (parent::editSingleEntry($values, $condition))
			{
				$this->sendNewPassword($plainTextPassword, $email);
			}
		}
		// TODO: Remove this comment when we have a mailserver working
		// header("location: " .$_SERVER["PHP_SELF"]."");
	}

	private function sendNewPassword($plainTextPassword, $email)
	{
		ini_set("SMTP", "mailout.one.com");
		ini_set("sendmail_from", "info@olacademy.com");

		$to = $email;
		$subject = "Återställning av lösenord";
		$name = "OL-Academy";
		$message = "Ditt nya lösenord: " . $plainTextPassword . 
		"Se till att ändra lösenordet snarast."; 
		
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "Content-Transfer-Encoding: 8bit\r\n";
		$headers .= "From: $name\r\nReply-To: $name\r\nReturn-Path: $name\r\n";
			
		if (mail ($to, $subject, $message, $headers))
			echo "Nytt lösenord skickat till " . $email . " " . $plainTextPassword;
		else
			echo "Något gick fel.";
	}

	private function generateRandomPassword()
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$length = rand(8, 12);
        $str = '';
		$max = mb_strlen($alphabet, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) 
		{
		    // random_int not found -> https://github.com/jasonhinkle/php-gpg/issues/27
		    $str .= $alphabet[  rand(0, $max)]; 
		}
		return $str;
	}
}