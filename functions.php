<?php

function getPosts($connect)
{
  $posts = mysqli_query($connect, "SELECT * FROM `posts`");
  $postsList = [];
  while ($post = mysqli_fetch_assoc($posts)) {
    $postsList[] = $post;
  }
  echo json_encode($postsList);
}

;

function getPost($connect, $id)
{
  $query = "SELECT * FROM `posts` WHERE `id` = ?";
  $stmt = mysqli_prepare($connect, $query);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      // Запрос выполнен успешно
      $postResult = mysqli_stmt_get_result($stmt);

      if ($row = mysqli_fetch_assoc($postResult)) {
        // Вернуть результат запроса
        http_response_code(200); // Статус 200 OK
        echo json_encode($row);
      } else {
        // Запись не найдена
        http_response_code(404); // Статус 404 Not Found
        echo json_encode(["status" => false, "error" => "Post not found."]);
      }
    } else {
      // Обработка ошибки, если запрос не выполнен
      http_response_code(500); // Статус 500 Internal Server Error
      echo json_encode(["status" => false, "error" => "Failed to retrieve the post."]);
    }

  }
}

function addPost($connect, $data)
{
  $title = $data['title'];
  $body = $data['body'];

  // Подготовленный запрос с параметрами
  $query = "INSERT INTO `posts` (`title`, `body`) VALUES (?, ?)";
  $stmt = mysqli_prepare($connect, $query);

  if ($stmt) {
    // Привязываем параметры к переменным
    mysqli_stmt_bind_param($stmt, "ss", $title, $body);

    // Выполняем подготовленный запрос
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      http_response_code(201);
      $res = [
          "status" => true,
          "post_id" => mysqli_insert_id($connect),
      ];
      echo json_encode($res);
    } else {
      // Обработка ошибки, если запрос не выполнен
      http_response_code(500);
      echo json_encode(["status" => false, "error" => "Failed to insert the post."]);
    }

    // Закрыть подготовленный запрос
    mysqli_stmt_close($stmt);
  } else {
    // Обработка ошибки, если подготовленный запрос не удался
    http_response_code(500);
    echo json_encode(["status" => false, "error" => "Failed to prepare the statement."]);
  }
}

function updatePost($connect, $id, $data)
{
  $title = $data['title'];
  $body = $data['body'];

  // Подготовленный запрос с параметрами
  $query = "UPDATE `posts` SET `title` = ?, `body` = ? WHERE `posts`.`id` = ?;";
  $stmt = mysqli_prepare($connect, $query);

  if ($stmt) {
    // Привязываем параметры к переменным
    mysqli_stmt_bind_param($stmt, "ssi", $title, $body, $id);

    // Выполняем подготовленный запрос
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      http_response_code(200);
      $res = [
          "status" => true,
          "message" => "Post updated successfully",
      ];
      echo json_encode($res);
    } else {
      // Обработка ошибки, если запрос не выполнен
      http_response_code(500); // Статус 500 Internal Server Error
      echo json_encode(["status" => false, "error" => "Failed to update the post."]);
    }

    // Закрыть подготовленный запрос
    mysqli_stmt_close($stmt);
  } else {
    // Обработка ошибки, если подготовленный запрос не удался
    http_response_code(500); // Статус 500 Internal Server Error
    echo json_encode(["status" => false, "error" => "Failed to prepare the statement."]);
  }
}

function deletePost($connect, $id)
{
  $query = "DELETE FROM `posts` WHERE `id` = ?";
  $stmt = mysqli_prepare($connect, $query);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      // Запрос выполнен успешно
      http_response_code(200);
      $res = [
          "status" => true,
          "message" => "Post deleted successfully",
      ];
      echo json_encode($res);
    } else {
      // Обработка ошибки, если запрос не выполнен
      http_response_code(500);
      echo json_encode(["status" => false, "error" => "Failed to delete the post: " . mysqli_stmt_error($stmt)]);
    }

    // Закрыть подготовленный запрос
    mysqli_stmt_close($stmt);
  } else {
    // Обработка ошибки, если подготовленный запрос не удался
    http_response_code(500);
    echo json_encode(["status" => false, "error" => "Failed to prepare the statement."]);
  }
}


