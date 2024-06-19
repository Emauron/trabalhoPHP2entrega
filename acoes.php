<?php
// print_r($_REQUEST);

if((isset($_REQUEST['add-task']) && $_REQUEST['add-task'] != NULL) && $_REQUEST['add-task'] != ''){
    $task = $_REQUEST['add-task'];
    $task_name = $_REQUEST['add-task-name'];
    if(strlen($task_name) > 20){
        echo "<script>window.alert('Nome do lembrete não deve ultrapassar os 20 caracteres!');</script>";
    }else {
        $sqlData = "INSERT INTO tasks (task, name, data_task, id_usuario) VALUES ('$task', '$task_name' ,'$set_data', $id_usuario);";
        actionDB($sqlData);
    }
}else if((isset($_REQUEST['remove-task']) && $_REQUEST['remove-task'] != NULL) && $_REQUEST['remove-task'] != ''){
    $id = $_REQUEST['remove-task'];
    $sqlData = "DELETE FROM `tasks` WHERE id = $id";
    actionDB($sqlData);
}else if((isset($_REQUEST['add_friend']) && $_REQUEST['add_friend'] != NULL) && $_REQUEST['add_friend'] != ''){
    $id_friend = $_REQUEST['add_friend'];

    $sqlFriendRequest = "SELECT id FROM `friends` WHERE user = $id_usuario AND friend = $id_friend;";
    $resultSqlFriendRequest = $PDO->query($sqlFriendRequest);
    if($resultSqlFriendRequest->rowCount() == 0){
        $sqlData = "INSERT INTO friends (user, friend) VALUES ('$id_usuario' ,'$id_friend');";
        $lasInsertId = actionDB($sqlData);
        if(is_numeric($lasInsertId) && $lasInsertId != ''){
            echo "<script>window.alert('Solicitação de amizade enviada!');</script>";
        }
    }
}else if((isset($_REQUEST['accept_friend_request']) && $_REQUEST['accept_friend_request'] != NULL) && $_REQUEST['accept_friend_request'] != ''){
    $id_friend = $_REQUEST['accept_friend_request'];
    $sqlFriendRequest = "UPDATE friends SET status_cadastro = 1 WHERE id ='$id_friend';";
    actionDB($sqlFriendRequest);
    echo "<script>window.alert('Amizade aceita com sucesso!');</script>";
}else if((isset($_REQUEST['deny_friend_request']) && $_REQUEST['deny_friend_request'] != NULL) && $_REQUEST['deny_friend_request'] != ''){
    $id_friend = $_REQUEST['deny_friend_request'];
    $sqlFriendRequest = "DELETE FROM friends WHERE id ='$id_friend';";
    actionDB($sqlFriendRequest);
    echo "<script>window.alert('Pedido de amizade recusado!');</script>";
}else if((isset($_REQUEST['checkbox_user_share_task']) && $_REQUEST['checkbox_user_share_task'] != NULL) && $_REQUEST['checkbox_user_share_task'] != ''){
    $user_share_task = $_REQUEST['checkbox_user_share_task'];
    $id_task = $_REQUEST['id_task'];

    $sqlData = "";
    foreach ($user_share_task as $user => $id_user_shared) {
        $sqlData .= "INSERT INTO tasks_shared (id_user, id_task, id_user_shared) VALUES ('$id_usuario', '$id_task','$id_user_shared');";
    }
    actionDB($sqlData);
    echo "<script>window.alert('Atividade compartilhada com sucesso!');</script>";
}else if((isset($_REQUEST['accept_task_shared']) && $_REQUEST['accept_task_shared'] != NULL) && $_REQUEST['accept_task_shared'] != ''){
    $id_task_shared = $_REQUEST['accept_task_shared'];
    $sqlTaskShared = "UPDATE tasks_shared SET status_cadastro = 1 WHERE id ='$id_task_shared';";
    actionDB($sqlTaskShared);
}else if((isset($_REQUEST['deny_task_shared']) && $_REQUEST['deny_task_shared'] != NULL) && $_REQUEST['deny_task_shared'] != ''){
    $id_task_shared = $_REQUEST['deny_task_shared'];
    $sqlTaskShared = "DELETE FROM tasks_shared WHERE id ='$id_task_shared';";
    actionDB($sqlTaskShared);
}else if((isset($_REQUEST['check_task']) && $_REQUEST['check_task'] != NULL) && $_REQUEST['check_task'] != ''){
    $id_task = $_REQUEST['check_task'];
    $sqlCheckTask = "UPDATE tasks SET status_cadastro = 1 WHERE id ='$id_task';";
    actionDB($sqlCheckTask);
}else if((isset($_REQUEST['edit_task']) && $_REQUEST['edit_task'] != NULL) && $_REQUEST['edit_task'] != ''){
    $id_task        = $_REQUEST['id_task'];
    $edit_task      = $_REQUEST['edit_task'];
    $edit_task_name = $_REQUEST['edit_task_name'];
    if(trim($edit_task) != ''){
        $sqlEditTask = "UPDATE tasks SET task = '$edit_task', name = '$edit_task_name' WHERE id ='$id_task';";
        actionDB($sqlEditTask);
        echo "<script>window.alert('Atividade alterada com sucesso!');</script>";
    }
}
?>