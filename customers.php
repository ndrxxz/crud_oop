<?php
session_start();
class Customers{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "customers";
    private $conn;

    // database connection
    public function __construct(){
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if(! $this->conn->connect_error){
            die ("Connection failed: " . $this->conn->connect_error);
        }
    }

    // insert customer data into customer table
    public function insertData($post){
        $name = $this->conn->real_escape_string($_POST['name']);
        $email = $this->conn->real_escape_string($_POST['email']);
        $username = $this->conn->real_escape_string($_POST['username']);
        $password = $this->conn->real_escape_string(md5($_POST['password']));

        $query = "INSERT INTO customer(name, email, username, password) VALUES('$name', '$email', '$username', '$password')";
        $sql = $this->conn->query($query);

        if($sql){
            $_SESSION['success'] = "Registration Successful!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Registration Failed!";
            header("Location: index.php");
            exit();
        }
    }

    // fetch customer records for show listing
    public function displayData(){
        $query = "SELECT * FROM customer";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            $data = array();

            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            return $data;

        } else {
            $_SESSION['error'] = "No record found!";
        }
    }

    // fetch single data for edit from customer table
    public function displayRecordId($id){
        $query = "SELECT * FROM customer WHERE id = '$id'";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            return $row;
        } else {
            $_SESSION['error'] = "No record found!";
        }
    }

    // update customer data into customer table
    public function updateRecord($postData){
        $name = $this->conn->real_escape_string($_POST['uname']);
        $email = $this->conn->real_escape_string($_POST['uemail']);
        $username = $this->conn->real_escape_string($_POST['upname']);
        $id = $this->conn->real_escape_string($_POST['id']);

        if(!empty($id) && !empty($postData)){
            $query = "UPDATE customer SET name = '$name', email = '$email', username = '$username' WHERE id = '$id'";
            $sql = $this->conn->query($query);

            if($sql){
                $_SESSION['success'] = "Record Updated!";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Error updating record!";
                header("Location: index.php");
                exit();
            }
        }
    }

    // delete customer data from customer table
    public function deleteRecord($id){
        $query = "DELETE FROM customer WHERE id = '$id'";
        $sql = $this->conn->query($query);

        if($sql){
            $_SESSION['success'] = "Deleted Successfully!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to delete!";
            header("Location: index.php");
            exit();
        }
    }
}

?>