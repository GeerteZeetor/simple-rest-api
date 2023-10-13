<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');
/**
 * @var PDO $connect
 */
require 'connect.php';
require 'functions.php';

if (mysqli_connect_errno()) {
  printf("Соединение не удалось: %s\n", mysqli_connect_error());
  exit();
}
$method = $_SERVER['REQUEST_METHOD'];
$q = $_GET['q'];
$params = explode('/', $q);

$type = $params[0];
isset($params[1]) && $id = $params[1];


switch ($method) {
  case 'GET':
    if ($type === 'posts') {
      if (isset($id)) {
        getPost($connect, $id);
      } else {
        getPosts($connect);
      }
    }
    break;
  case 'POST':
    if ($type === 'posts') {
      addPost($connect, $_POST);
    }
    break;
  case 'PATCH':
    if ($type === 'posts') {
      if (isset($id)) {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        updatePost($connect, $id, $data);
      }
    }
    break;
    case 'DELETE':
    if ($type === 'posts') {
      if (isset($id)) {
        deletePost($connect, $id);
      }
    }
    break;
}

