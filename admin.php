<style>

h3
{
    margin-left:1%;
}
hr
{
    width:70%;
    float:left;
    border-color:#FFF;
    margin-left:1%;
    margin-top:0%;
}
#wrapper
{
    width:100%;
}
#adminHeader
{
    text-align:center;
    vertical-align:middle;
    width:77%;
    float:right;
    background-color:blue;
    margin-left:1%;
    margin-right:1%;
}
#adminTable
{
    margin-left:1%;
    background-color:green;
    width:20%;
    float:left;
}
#adminDiv
{
    background-color:red;
    min-width:50%;
    overflow: hidden; /* # no overlap on parent divs*/
    margin-left: 1%;
    margin-right: 1%;
    margin-top: 8%;
}
ol
{
    margin:0%;
    padding:0%;
    padding-left:9%;
}
</style>
<?php
/*
 * Nu är frågan, hur fan ska allt presenteras.
 * Layout.
 * 1%   20%     1%         77%          1%
 * - |div menu| - | div header thingy | - 
 * ----------------------------------
 * - | div där all admin stuff hamnar |
 */
$pageTitle = " - Admin";
include("include/header.php");

/*
 * Functions for the options
 */

function calenderAdmin()
{
    return "Kalender!";
}

function newsAdmin($db)
{
    $sql = "SELECT * FROM news";
    $res = $db->queryAndFetch($sql);
    
    $table = "";
    foreach($res as $key)
    {
        
    }
    return $table;
}

function guestbookAdmim()
{
    return "Gästboken!";
}


if($priviledge == 2)
{
    $menu = " <div id='wrapper'>
                <h3> Admin page </h3><hr/>
                <div id='adminHeader'>";
    $admin = "<div id='adminDiv'>";
    if(isset($_GET['p']) && is_numeric($_GET['p']))
    {
        $p = $_GET['p'];
    
        switch($p)
        {
            case 1:
                $menu .= "<h4>Kalendar admin</h4>";
                $admin .= calenderAdmin();
                break;
                
            case 2:
                $menu .= "<h4>Nyhet admin</h4>";
                $admin .= newsAdmin($db);
                break;
                
            case 3: 
                $menu .= "<h4>Gästbok admin</h4>";
                $admin .= guestbookAdmim();
                break;
                
            default :
                $menu .= "<h4>Välj från menyn till vänster</h4>";
        }
    }
    else
    {
        $menu .= "<h4>Välj från menyn till vänster</h4>";
    }
    $admin .="</div>";
    $menu .= "</div>";
    $menu .= "<div id='adminTable'>
                    <table>
                    <li> <a href='admin.php?p=1'>Lägg till kalender sak</a></li>";
                    if($p==1) $menu .= "<ol> Test </ol>"; 
    $menu .=       "<li> <a href='admin.php?p=2'>Lägg till nyhet sak</a></li>";
                    if($p==2) $menu .= "<ol> Test </ol>";
    $menu .=       "<li> <a href='admin.php?p=3'>Lägg till gästboksak sak</a></li>";
                    if($p==3) $menu .= "<ol> Test </ol>";
    $menu .=        "</table>
                </div>
                </div>";
    
    

}
else
{
    $menu = displayErrorMessage("YOU HAVE NO POWER HERE, GANDALF STORMCROW");
    $admin = "";
}
echo $menu;
echo $admin;
