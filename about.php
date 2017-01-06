<?php
$pageId = "about";
$pageTitle = " - Om";
include ("include/header.php");

$filename = "include/about.html";
$fileContent = file_get_contents( $filename );

function putContentToFile($filename)
{
    if( validateStringPOST( "aboutContent" ) )
    {
        $newContent = strip_tags( $_POST["aboutContent"], "<p><ul><li><table><a><span><h3><h2><h1><h4><hr><br>");

        if( file_put_contents( $filename, $newContent ) )
        {
            header( "location: about.php" );
        }
    }
}

if( isset( $_GET["edit"] ) && $user->isAdmin() )
{
    $aboutText = "<form method='post'><textarea id='about_textarea' name='aboutContent' spellcheck='false' rows='20'>" . $fileContent . "</textarea><input class='btn btn-primary' type='submit' name='save' value='Spara'/></form>";
}
else
{
    $aboutText = $fileContent;
    if( $user->isAdmin() )
    {
        $aboutText .= "<br/><hr/><a href='about.php?edit'> Editera innehåll</a>";
    }
}

if( isset( $_POST["save"] ) && $user->isAdmin() )
{
    try
    {
        putContentToFile( $filename );
    }
    catch(Exception $e)
    {
        populateError("Exception : Failed to open stream: Permission denied");
    }

}
displayError();
echo "<div style='clear:both; overflow: hidden;'>";
echo $aboutText;
echo "</div>";

include ("include/footer.php");
