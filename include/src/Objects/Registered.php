<?php
class Registered extends DataObject
{
	public function __construct()
	{
		parent::__construct("registered");
	}

	public function removeSingleRegistered($id)
	{
		parent::removeSingleEntryById($condition = array("eventID" => $id));
	}
}