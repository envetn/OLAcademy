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

function newsAdmin()
{
    return "Nyheter!";
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
    $admin = "<div id='adminDiv'> Du valde: ";
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
                $admin .= newsAdmin();
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
                    <li> <a href='admin.php?p=1'>Lägg till kalender sak</a></li>
                    <li> <a href='admin.php?p=2'>Lägg till nyhet sak</a></li>
                    <li> <a href='admin.php?p=3'>Lägg till gästboksak sak</a></li>
                   </table>
                </div>
                </div>";
    
    

}
else
{
    $menu = displayErrorMessage("YOU HAVE NO POWER HERE, GANDALF STORMCROW");
}
echo $menu;
echo $admin;
