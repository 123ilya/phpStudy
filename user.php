<?php

class User // Создаём класс User
{
    protected $email;
    protected $nickName;
    protected $name;
    protected $age;
    protected $id;

    public function __construct($email, $nickName, $name, $age)
    {
        $this->email = $email;
        $this->nickName = $nickName;
        $this->name = $name;
        $this->age = $age;
        $this->id = rand(1, 100);
    }
}

class Storage //Создаём класс Storage
{
    const FILE_PATH = './users.json'; //Константа хранящая путь к файлу - хранилищу.
    public $objectsList = []; // Массив, хранящий объекты, созданные при помощи класса User
    public function store() // функция, записывающая в файл, инкодированный в формат json массив $objectsList
    {
        file_put_contents(self::FILE_PATH, json_encode($this->objectsList));
    }
    public function read() // Функция, читает содержание файла users.json, декодирует его и
    // перезаписывает этим содержанием переменную $objectsList.
    {
        if (file_exists(self::FILE_PATH)) {
            $this->objectsList = json_decode(file_get_contents(self::FILE_PATH));
        }
    }
}

// C - POST /user.php
// R - GET /user.php?id=1
// U - PUT /user.php
// D - DELETE /user.php?id=1
// R - GET /user.php
class UserStorage extends Storage
{
    public function addUser()
    {
        $user = new User($_POST['email'], $_POST['nickName'], $_POST['name'], $_POST['age']);
        $this->objectsList[] = $user;
    }

    public function deleteUser($id)
    {
        foreach ($this->objectsList as $key => $user) {
            if ($user->id === $id) {
                unset($this->objectsList[$key]);
            }
            break;
        }
    }

    public function updateUser()
    {
        foreach ($this->objectsList as $key => $user) {
            if ($user->id === $_POST['id']) {
                $this->objectsList['key']->email = $_POST['email'];
                $this->objectsList['nickName'] = $_POST['nickName'];
                $this->objectsList['name'] = $_POST['name'];
                $$this->objectsList['age'] = $_POST['age'];
            }
            break;
        }
    }

    public function getUser($id)
    {
        foreach ($this->objectsList as $key => $user) {
            if ($user->id === $id) {
                return json_encode($user);
            }
        }
        return null;
    }

    public function getUsers() 
    {
        return json_encode($this->objectsList);
    }
}

$userStorage = new UserStorage();

switch ($_SERVER['REQUEST_METHOD']) {
case 'POST':
    $userStorage->addUser();
    break;
case 'GET':
    if (isset($_GET['id'])) {
        $userStorage->getUser($_GET['id']);
    } else {$userStorage->getUsers();}
    break;
case 'PUT':
    $userStorage->updateUser();
    break;
case 'DELETE':
    if (isset($_GET['id'])) {
        $userStorage->deleteUser($_GET['id']);
    }
    break;
}
