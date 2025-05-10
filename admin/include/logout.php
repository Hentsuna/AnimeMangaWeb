<?php
class logout
{
    function logoutuser(){
        unset($_SESSION['user']);
        session_destroy();
    }
}
?>