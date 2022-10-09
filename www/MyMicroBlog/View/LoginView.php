<?php
namespace MyMicroBlog\View;

class LoginView
{
    private string $formAction;

    public function __construct()
    {
        $this->formAction = $_SERVER['PHP_SELF'];
    }

    public function showRegistrationForm(): void
    {
        global $aErrors;
        $sUsername = "";
        $sMail = "";

        if (isset($_REQUEST['usernamereg']) && count($aErrors) > 0) {
            $sUsername = $_REQUEST['usernamereg'];
        }
        if (isset($_REQUEST['email']) && count($aErrors) > 0) {
            $sMail = $_REQUEST['email'];
        }

        function showUsernameReg(string $sUsername): string
        {
            $res = '';
            if (isset($aErrors['usernamereg'])) {
                $res .= "<input type='text' name='usernamereg' id='usernamereg' class='error form-control' value='$sUsername'>";
                $res .= $aErrors['usernamereg'];
            } else {
                $res .= "<input type='text' name='usernamereg' id='usernamereg'  class='form-control' value='$sUsername'>";
            }
            return $res;
        }

        function showPasswordReg(): string
        {
            $res = '';
            if (isset($aErrors['password1'])) {
                $res .= "<input type='password' name='password1' id='password1' class='error form-control'>";
                $res .= $aErrors['password1'];
            } else {
                $res .= "<input type='password' name='password1' class='form-control' id='password1'>";
            }
            return $res;
        }

        function showEmailReg(string $sMail): string
        {
            $res = '';
            if (isset($aErrors['email'])) {
                $res .= "<input type='email' name='email' id='email' class='error form-control' value='$sMail'>";
                $res .= $aErrors['email'];
            } else $res .= "<input type='email' name='email' id='email' class='form-control' value='$sMail'>";
            return $res;
        }

        echo "<br><h3>Register:</h3>
    <form action='$this->formAction' method='post'>
    <div class='form-floating'>" .
            showUsernameReg($sUsername) .
            "
    <label for='usernamereg' class='form-label'>Username:</label>
    </div>
        <div class='form-floating'>" .
            showPasswordReg() .
            "
        <label for='password1' class='form-label'>Password:</label>
        </div>
        <div class='form-floating'>
        <input type='password' class='form-control' name='password2' id='password2' >
        <label for='password2' class='form-label'> Repeat:</label >
        </div>
        <div class='form-floating'>" .
            showEmailReg($sMail) .
            "
        <label for='email' class='form-label'> eMail:</label >
        </div>
        <input type='hidden' name='action' value='register' >
        <input type='submit' class='btn btn-primary' value='Register' >
    </form >";
    }


    public function showLoginForm(): void
    {
        echo "<h3>Login:</h3>
    <form action='$this->formAction' method='post'>
        <div class='form-floating'>
        <input type='text' class='form-control' name='username' id='username'>
        <label for='username' class='form-label'>Username:</label>
        </div>
        <div class='form-floating'>
        <input type='password' class='form-control' name='password' id='password'>
        <label for='password' class='form-label'>Password:</label>
        </div>
        <input type='hidden' name='action' value='login'>
        <input type='submit' class='btn btn-primary' value='Login'>
    </form>";
    }

    public function showLogoutForm(): void
    {
        echo "<h2>Hallo '" . getCurrentUser()->name . "'</h2>
    <form action='$this->formAction' method='post'>
        <input type='hidden' name='action' value='logout'>
        <input type='submit' class='btn btn-primary' value='Logout'>
    </form>";
    }
}