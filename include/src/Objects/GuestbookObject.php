<?php
class GuestbookObject extends DataObject
{
    private $today;

    function __construct()
    {
        parent::__construct("posts");
        $this->today = date("Y-m-d H:i:s");
    }

    public function editSingleEntryById($id, $params)
    {
        // TO be implemented
    }

    public function insertEntyToDatabase($values)
    {
        $values['added'] = $this->today;
        parent::insertEntyToDatabase($values);
    }

    public function isAllowedToDeleteEntry($id)
    {
        if (isset($_SESSION['privilege']) && $_SESSION['privilege'] === "2")
        {
            return true;
        }
        return false;
    }
}
