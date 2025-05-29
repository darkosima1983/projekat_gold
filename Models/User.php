<?php
require_once "Baza.php";
require_once "session_start.php";
class User extends Baza
{   
    public function register ($firstName, $lastName, $email, $password)
    {
        $firstName = $this->sql->real_escape_string ($firstName);
        $lastName = $this->sql->real_escape_string ($lastName);
        $email = $this->sql->real_escape_string ($email);
        $password = $this->sql->real_escape_string ($password);
        $password = password_hash($password, PASSWORD_BCRYPT);
        $result = $this->sql->query ("SELECT * FROM user WHERE email= '$email'");
          if ($result -> num_rows >= 1)
        {
            echo "<script> alert ('Korisnik vec postoji'); 
                        window.location.href = 'login.php';</script>";
        }
        else 
    if ($this->sql->query("INSERT INTO user (first_name, last_name, email, password) VALUES ('$firstName','$lastName', '$email', '$password')")) 
    {
        echo "<script>alert('Uspešno ste se registrovali!'); window.location.href = 'login.php';</script>";
        exit;
    } else 
    {
        die("Greška pri upisu: " . $this->sql->error);
    }
    }
      public function login($email, $password)
    {
        $email = $this->sql->real_escape_string ($email);
        $password = $this->sql->real_escape_string ($password);
        $result = $this->sql->query("SELECT * FROM user WHERE email = '$email'");
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $verifypassword = password_verify ($password, $user['password']);
            if ($verifypassword == true)
            {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['log'] = true;
                echo "<script> alert ('Uspesno ste se prijavili'); 
                window.location.href = 'kalkulator.php';</script>";
            }
            else
            {
                echo "<script> alert ('Pogresna lozinka'); 
                window.location.href = 'login.php';</script>";
            }
           
        }
        else
        {
            echo "<script> alert ('Korisnik ne postoji');
            window.location.href = 'login.php';</script>";
        }
    }
}



