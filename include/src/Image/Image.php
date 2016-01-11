<?php
define('MAX_SIZE', 5242880);
class Image
{
	private static $maxSize = 5242880;
	private $name;
	private $fileName;
	private $fileTmpLo;
	private $fileType;
	private $fileSize;
	private $fileErrorMsg;
	private $debug;

	function __construct($name, $debug)
	{
		$this->name = $name;
		
		$this->fileName = $_FILES[$this->name]["name"];
		$this->fileTmpLoc = $_FILES[$this->name]["tmp_name"];
		$this->fileType = $_FILES[$this->name]["type"];
		$this->fileSize = $_FILES[$this->name]["size"];
		$this->fileErrorMsg = $_FILES[$this->name]["error"];
		$this->debug = $debug;
	}

	public function uploadImage()
	{
		$kaboom = explode(".", $_FILES[$this->name]["name"]);
		$fileExt = end($kaboom);
		
		$this->fileName = uniqid(); // enough for now
		$this->fileName .= "." . $fileExt;
		$filePath =/* IMAGE_PATH . */"img/newsImg/";
		
		if (! move_uploaded_file($this->fileTmpLoc, $filePath . $this->fileName))
		{
			echo "ERROR: File not uploaded. Try again.";
			unlink($this->fileTmpLoc);
			exit();
		}
		
		if ($this->debug)
		{
			$this->imageDebug();
		}
		return $filePath . $this->fileName;
	}

	public function validateFile()
	{
		
		// Need a better way of handling error..
		if (! $this->fileTmpLoc)
		{
			echo "ERROR: Please browse for a file before clicking the upload button.";
			echo $this->fileTmpLoc;
			exit();
		}
		else if ($this->fileSize > MAX_SIZE)
		{
			echo "ERROR: Your file was larger than 5 Megabytes in size.";
			unlink($this->fileTmpLoc);
			exit();
		}
		else if (! preg_match("/.(gif|jpg|png|jpeg)$/i", $this->fileName))
		{
			echo "ERROR: Your image was not .gif, .jpg, or .png.";
			unlink($this->fileTmpLoc);
			exit();
		}
		else if ($this->fileErrorMsg == 1)
		{
			echo "ERROR: An error occured while processing the file. Try again.";
			exit();
		}
	}

	private function imageDebug()
	{
		$kaboom = explode(".", $this->fileName);
		$fileExt = end($kaboom);
		
		echo $this->fileTmpLoc . "<br/>";
		echo 'The file named <strong>' . $this->fileName . '</strong> uploaded successfuly.<br /><br />';
		echo 'File size is:  <strong>' . $this->fileSize . '</strong> bytes in size.<br /><br />';
		echo 'It is an <strong>' . $this->fileType . '</strong> type of file.<br /><br />';
		echo "The file extension is <strong>$fileExt</strong><br /><br />";
		echo 'The Error Message output for this upload is:' . $this->fileErrorMsg;
	}
}