<style>
table
{
    margin-left:2%;
}
th
{
    text-align: center;
}
td
{
    border: 1px solid black;
    margin-left:2%;
}
td span
{
     margin-left:2%;
}
.admin_news_remove
{
    text-align: center;
}
input
{
	margin:0px;
	padding:0px;
}
</style>
<?php
$pageTitle = " - Admin";
include("include/header.php");
$privilege  = getUserprivilege($db);

/*
 * Functions for the options
 */
function showTitleOfPosts($db)
{
    $sql = "SELECT title,id,author FROM news LIMIT 10";

    $res = $db->queryAndFetch($sql);
    $news = "<h3>Nyheter</h3><table >
    <tr>
        <th>Title</th><th>Av</th><th>Ta bort</th>
    <tr>";
    foreach($res as $row)
    {
        $title = $row->title;
        $author = $row->author;
        if(strlen($title) > 20)
        {
            $title =  substr($title,0, 20). " ...";
        }
        if(strlen($title) > 20)
        {
            $title =  substr($title,0, 20). " ...";
        }
        
        $news .= "
                    <tr>
                        <td><span>".$title."</span></td>
                        <td><span>".$row->author."</span></td>
                        <td>
                            <a class='admin_news_remove' href='admin.php?r=".$row->id."'><img src='img/cross.png' width=18px height=18px></a>
                            <a class='admin_news_remove' href='news.php?e=".$row->id."'><img src='img/edit.jpg' width=18px height=18px></a>
                            <a class='admin_news_remove' href='news.php?offset=0&p=".$row->id."'>Show</a>
                        </td>
                    </tr>";
    }
    $news .= "</table>";
    return $news;
}

function showEvents($db)
{
    $res = getEvents($db);
    $htmlEvents = "<h3>Veckans träningar</h3><table>
    <tr>
        <th>Event</th><th>Info</th><th>När</th><th>Datum</th><th>Edit</th>
    <tr>";
    foreach($res as $event)
    {
        $htmlEvents .= "<tr>
                            <td>".$event->eventName."</td>
                            <td>".$event->info."</td>
                            <td>".$event->startTime."</td>
                            <td>".$event->date."</td>
                            <td>
                                <a class='admin_news_remove' href='admin.php?r=".$event->id."'><img src='img/cross.png' width=18px height=18px></a>
                                <a class='admin_news_remove' href='event.php?e=".$event->id."'><img src='img/edit.jpg' width=18px height=18px></a>
                                <a class='admin_news_remove' href='event.php?offset=0&p=".$event->id."'>Show</a>
                            </td>
                        </tr>";
    }
    return $htmlEvents . "</table>";
}

function getUsers($db)
{
    $sql = "SELECT id,name,email,Privilege,regDate FROM users";
    $res = $db->queryAndFetch($sql);

    // Maybe not show all users?
    $htmlUsers = "<h3>Användare</h3><form method='post'><table>
    <tr>
        <th>Namn</th><th>email</th><th>Rättighet</th><th>Registrerad</th><th>Edit</th>
    <tr>";

    foreach($res as $users)
    {
		$htmlUsers .= "<tr>
                            <td><input type='text' name='username' value='".$users->name."' /></td>
                            <td>".$users->email."</td>
                            <td><input type='text' name='privilege' value='".$users->Privilege."' /></td>
                            <td>".$users->regDate."</td>
                            <td>
								<input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='editUser_1' value='Click me'>
                                <input type='image' src='img/edit.jpg'  border='0' width=18px height=18px alt='Submit'  name='editUser_2' value='Click me2'>
                            </td>
                        </tr>";
    }
    return $htmlUsers . "</table></form>";
}


if($privilege === "2")
{
    $htmlAdmin = "";
    if(isset($_POST['editUser_1_x'])) // Where does x come from?
    {
    	// Remove user
        $htmlAdmin .= "<h4 style='text-align:center;'> User removed </h4>";
    }
    else if(isset($_POST['editUser_2_x']))
    {
        // Update user
        $htmlAdmin .= "<h4 style='text-align:center;'> User updated </h4>";
    }
    
    
    $htmlAdmin .= showTitleOfPosts($db);
    $htmlAdmin .= showEvents($db);
    $htmlAdmin .= getUsers($db);
    echo "<div class='row clearFix'>";
    echo $htmlAdmin;
    echo "</div>";
}
else
{
    echo displayErrorMessage("BEGONE!!!");
}
