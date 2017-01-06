 <?php
class NewsObject extends DataObject
{
    private $today;
    private $image;

    function __construct()
    {
        parent::__construct("news");
        $this->today = date("Y-m-d H:i:s");
    }

    public function editSingleEntryById($id, $params)
    {
        $sql = "UPDATE news SET title=?, content=?, author=?, added=? WHERE id=?";
        $this->database->ExecuteQuery($sql, $params);
    }

    public function insertEntyToDatabase($values)
    {
        $values["added"] = $this->today;
        parent::insertEntyToDatabase($values);
    }

    public function isAllowedToDeleteEntry()
    {
        if (isset($_SESSION["privilege"]) && $_SESSION["privilege"] === "2")
        {
            return true;
        }
        return false;
    }

    public function uploadImage($debug = false)
    {
        $this->image = new Image("uploaded_file", $debug);
        if($this->image->validateFile())
        {
            return $this->image->uploadImage();
        }
    }
}
?>
