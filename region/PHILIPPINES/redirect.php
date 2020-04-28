<?php
if($_POST['country']=="INDIA")
{
    header("Location: login.php");  
}
else
{
    header("Location: loginphilippines.php"); 
}
?>