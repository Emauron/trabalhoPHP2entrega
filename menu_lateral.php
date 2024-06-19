<?php
    $data_menu = (isset($_SESSION['set_data']) && $_SESSION['set_data'] != NULL) ? $_SESSION['set_data'] : '';
?>
<div class="menu-lateral col col-md-3">
    <div class="col col-md-12 p-3 ms-2">
        <form action="index.php" method="get" class="d-flex justify-content-center align-items-center">
            <input type="date" name="set_data" id="set_data" value="<?=($data_menu != null) ? $data_menu : '';?>">
            <button class="btn btn-info btn-search"><i class="fa fa-search text-white"></i></button>
        </form>
    </div>
    <?php
        if((isset($_REQUEST['friend-search']) && $_REQUEST['friend-search'] != NULL) && $_REQUEST['friend-search'] != ''){
            $usuer_name = $_REQUEST['friend-search'];
    ?>
        <div class="col col-md-12 p-3 bg-dark ms-2">
            <h4 class="text-white text-center">Usuários</h4>
            <div >
                <ul>
                <?php
                    echo $sqlNome    = "SELECT id, nome FROM usuarios WHERE nome LIKE '%$usuer_name%' AND id <> $id_usuario;";
                    $resultNome = $PDO->query($sqlNome);
                    while($consultaNome = $resultNome->fetch(PDO::FETCH_OBJ)){
                ?>
                    <li class="text-white list-tasks friend-search-li">
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <div>
                                <h5 id="task-name"><?=$consultaNome->nome?></h5>
                                <span>Enviar pedido de amizade</span>
                            </div>
                            <a href="index.php?set_data=<?=$data_menu?>&add_friend=<?=$consultaNome->id?>" class="btn btn-info btn-add-friend">
                                <i class="fa fa-plus"></i> <i class="fa fa-user"></i>
                            </a>
                        </div>
                    </li>
                <?php
                    }
                ?>
                </ul>
            </div>
        </div>
    <?php
        }else if(isset($_REQUEST['notifications'])){
    ?>
        <div class="col col-md-12 p-3 bg-dark ms-2">
            <h4 class="text-white text-center">Notificações</h4>
            <div>
                <ul>
                <?php
                    $sqlNotifications ="SELECT
                                            `friends`.id,
                                            `usuarios`.nome,
                                            'Pedido de Amizade' AS table_message
                                        FROM
                                            `friends`
                                        LEFT JOIN `usuarios` ON `friends`.user = `usuarios`.id
                                        WHERE
                                            `friends`.friend = '$id_usuario'
                                        AND `friends`.status_cadastro = 0
                                        UNION ALL
                                        SELECT
                                            `tasks_shared`.id,
                                            `tasks`.name,
                                            'Atividade Compartilhada' AS table_message
                                        FROM
                                            `tasks`
                                        RIGHT JOIN `tasks_shared` ON `tasks_shared`.`id_task` = `tasks`.id
                                        WHERE
                                            `tasks_shared`.id_user_shared  = '$id_usuario'
                                        ";
                    $resultNotifications         = $PDO->query($sqlNotifications);
                    $numResultNotifications      = $resultNotifications->rowCount();
                    if($numResultNotifications > 0){
                        while($consultaNotifications = $resultNotifications->fetch(PDO::FETCH_OBJ)){
                            if($consultaNotifications->table_message == 'Pedido de Amizade'){
                                $tabela = 'friends';
                            }else {
                                $tabela = 'tasks_shared';
                            }
                ?>
                            <li class="text-white list-tasks friend-search-li">
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <div class="d-flex align-items-center  justify-content-between"  style="width:100%;">
                                        <h5>
                                            <?=$consultaNotifications->nome?>
                                            <br>
                                            <span class="table_message"><?=$consultaNotifications->table_message?></span>
                                        </h5>
                                        <div class="d-flex">
                                            <form action="index.php" method="post">
                                                <input  
                                                    type="hidden"
                                                    name="<?=($tabela == 'tasks_shared') ? 'deny_task_shared' :'deny_friend_request';?>"
                                                    value="<?=$consultaNotifications->id?>"
                                                >
                                                <button class="btn text-success-outline" style="background-color:rgb(99, 99, 99);">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                            <form action="index.php" method="post" class="ms-1">
                                                <input  
                                                    type="hidden"
                                                    name="<?=($tabela == 'tasks_shared') ? 'accept_task_shared' :'accept_friend_request';?>"
                                                    value="<?=$consultaNotifications->id?>"
                                                >
                                                <button class="btn text-success-outline" style="background-color:rgb(99, 99, 99);">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </form>
                                </div>
                            </li>   
                <?php
                        }
                    }else {
                ?>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <div class="d-flex align-items-center  justify-content-center"  style="width:100%;">
                            <h5 class="text-white mt-2">Nenhuma notificação no momento...</h5>
                        </div>
                    </div>
                <?php
                    }
                ?>
                </ul>
            </div>
        </div>
            <?php
                }else if((isset($_REQUEST['share_task']) && $_REQUEST['share_task'] != NULL) && $_REQUEST['share_task'] != ''){
                    $id_task = $_REQUEST['share_task'];
            ?>
                <form action="index.php?set_data=<?=$data_menu?>" method="post" class="col col-md-12 p-3 bg-dark ms-2 text-center">
                <h4 class="text-white text-center">Amigos</h4>
                    <span class="text-white"><strong>Escolha um amigo para compartilhar a atividade</strong></span>
                    <?php
                        $sqlShareTask ="SELECT 
                                            `friends`.id,
                                            `usuarios`.id AS 'id_usuario', 
                                            `usuarios`.nome
                                        FROM
                                            `friends`
                                        LEFT JOIN 
                                            `usuarios`
                                        ON
                                            `friends`.user = `usuarios`.id
                                        OR 
                                            `friends`.friend = `usuarios`.id
                                        WHERE
                                            (
                                            `friends`.friend = '$id_usuario' OR `friends`.user = '$id_usuario' 
                                            )
                                        AND `friends`.status_cadastro = 1;";
                        $resultShareTask    = $PDO->query($sqlShareTask);
                        $numResultShareTask = $resultShareTask->rowCount();
                        if($numResultShareTask > 0){
                            while($consultaShareTask = $resultShareTask->fetch(PDO::FETCH_OBJ)){
                                if ($consultaShareTask->id_usuario == $id_usuario) {
                                    continue; 
                                }             
                   ?>
                                <li class="text-white list-tasks friend-search-li">
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <div class="d-flex align-items-center justify-content-between" style="width:100%;">
                                            <h5>
                                                <?=$consultaShareTask->nome?>
                                            </h5>
                                            <div class="float-end ms-auto d-flex form-check">
                                                <input type="checkbox" name="checkbox_user_share_task[]" value="<?=$consultaShareTask->id_usuario?>" class="form-check-input">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                    <?php
                            }
                        } else {
                    ?>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="d-flex align-items-center justify-content-center" style="width:100%;">
                                    <h5 class="text-white mt-2">Você ainda não possui nenhum amigo =[ </h5>
                                </div>
                            </div>
                    <?php   
                        }
                    ?>
                    <input type="hidden" name="id_task" value="<?=$id_task?>">
                    <button class="btn btn-success mt-2 col-12">
                        Enviar
                    </button>
                </form>
            <?php
                }else if((isset($_REQUEST['update_task']) && $_REQUEST['update_task'] != NULL) && $_REQUEST['update_task'] != ''){
            ?>
                    <form action="index.php?set_data=<?=$data_menu?>" method="post" class="d-flex justify-content-center flex-column bg-dark p-4">
                    <?php
                        $id_task = $_REQUEST['update_task'];
                        $sqlTasks    = "SELECT 
                                            id,
                                            name, 
                                            task,
                                            status_cadastro,
                                            'tasks' AS tabela
                                        FROM 
                                            tasks 
                                        WHERE 
                                            id = '$id_task';";
                        $resultTasks = $PDO->query($sqlTasks);
                        $consultaTasks = $resultTasks->fetch(PDO::FETCH_OBJ);
                    ?>
                        <h4 class="text-white text-center">Editar Atividade: <br> <?=$consultaTasks->name?></h4>
                        <input type="text" placeholder="Nome" name="edit_task_name" value="<?=$consultaTasks->name?>" class="float-start" id ="add-task-name">
                        <div class="d-flex align-items-center">
                            <input type="text" placeholder="Lembrete" name="edit_task" id="add-task" value="<?=$consultaTasks->task?>">
                        </div>
                        <input type="hidden" name="id_task" value="<?=$consultaTasks->id?>">
                        <div class="col col-12 mt-2">
                            <a href="index.php?set_data=<?=$set_data?>" class="btn btn-danger">
                                Cancelar
                            </a>
                            <button class="btn btn-success float-end">
                                Salvar
                            </button>
                        </div>
                    </form>
            <?php
                }else {
            ?>
        <div class="col col-md-12 p-3 bg-dark ms-2">
            <h4 class="text-white text-center">LEMBRETES <?php echo $day . '/' . $month . '/' . '' . $year?></h4>
            <div >
                <ul>
                <?php
                    $sqlTasks    = "SELECT 
                                        id,
                                        name, 
                                        task,
                                        status_cadastro,
                                        'tasks' AS tabela
                                    FROM 
                                        tasks 
                                    WHERE 
                                        data_task = '$data_menu' 
                                    AND id_usuario = $id_usuario
                                    UNION ALL
                                    SELECT
                                        `tasks`.id,
                                        `tasks`.name,
                                        `tasks`.task,
                                        `tasks`.status_cadastro,
                                        'tasks_shared' AS tabela
                                    FROM
                                        tasks
                                    RIGHT JOIN 
                                        `tasks_shared`
                                    ON
                                        `tasks_shared`.id_task = `tasks`.id
                                    WHERE 
                                        `tasks_shared`.id_user_shared = $id_usuario
                                    AND `tasks`.data_task = '$data_menu';";
                    $resultTasks = $PDO->query($sqlTasks);
                    while($consultaTasks = $resultTasks->fetch(PDO::FETCH_OBJ)){
                ?>
                        <li class="text-white list-tasks">
                            <div class="d-flex justify-content-between flex-column">
                                <div style="max-width: 90%;">
                                    <h5 id="task-name"><?=$consultaTasks->name?></h5>
                                    <p><?=$consultaTasks->task?></p>
                                </div>
                                <div class="d-flex float-start ms-auto align-items-center">
                                <?php
                                    if($consultaTasks->tabela == 'tasks'){
                                ?>
                                    <form action="index.php?set_data=<?=$data_menu?>" method="post">
                                        <input type="hidden" name="share_task" value="<?=$consultaTasks->id?>">
                                        
                                        <button class="btn btn-info btn-xs btn-remove-task">
                                            <i class="fa fa-share"></i>
                                        </button>
                                    </form>
                                    <form action="index.php?set_data=<?=$data_menu?>" method="post">
                                        <input type="hidden" name="remove-task" value="<?=$consultaTasks->id?>">
                                        <button class="btn btn-danger btn-remove-task">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                <?php
                                    }
                                    if($consultaTasks->status_cadastro == 0){
                                ?>
                                        <form action="index.php?set_data=<?=$data_menu?>" method="post">
                                            <input type="hidden" name="update_task" value="<?=$consultaTasks->id?>">
                                            <button class="btn btn-warning btn-remove-task">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </form>
                                        <form action="index.php?set_data=<?=$data_menu?>" method="post">
                                            <input type="hidden" name="check_task" value="<?=$consultaTasks->id?>">
                                            <button class="btn btn-success">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                <?php
                                    }
                                ?>
                                </div>
                            </div>
                        </li>
                <?php
                    }
                ?>
                </ul>
            </div>
            <form action="index.php?set_data=<?=$data_menu?>" method="post" class="d-flex justify-content-center flex-column">
                <input type="text" placeholder="Nome" name="add-task-name" class="float-start" id ="add-task-name" required>
                <div class="d-flex align-items-center">
                    <input type="text" placeholder="Lembrete" name="add-task" id="add-task">
                    <button class="btn btn-success btn-add-task"><i class="fa fa-plus text-white"></i></button>
                </div>
            </form>
        </div>
    <?php
        }
    ?>
</div>
