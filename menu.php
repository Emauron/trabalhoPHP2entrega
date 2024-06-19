<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <nav class="container-fluid nav-menu-top">
        <section class="row p-3">
            <div class="text-center text-white d-flex justify-content-center">
                <form class="friend-search" action="index.php" method="get">
                    <div>
                        <input type="text" placeholder="Procure por amigos aqui" name="friend-search" class="friend-search-bar ms-4">
                        <input type="hidden" name="set_data" value="<?=$data->format('Y-m-d')?>">
                        <i class="fa fa-user users-icon ms-auto"></i>
                    </div>
                    <button class="btn btn-info me-auto text-white d-flex align-items-center mt-1">Procurar  <i class="fa fa-search text-white ms-1" style="margin-right:100%;"></i></button>
                </form>
                <h3 class="d-flex">
                    <form action="index.php" method="get">
                    <?php
                        $prevMonth = clone $data;
                        $prevMonth->modify('-1 month');
                        $prevMonth->modify('first day of this month');
                    ?>
                        <input type="hidden" name="set_data" value="<?=$prevMonth->format('Y-m-d')?>">
                        <button class="btn-nav-month"> <i class="fa fa-chevron-left"></i></button>
                    </form>
                     CALENDÁRIO 
                    <form action="index.php" method="get">
                    <?php
                        $nextMonth = clone $data;
                        $nextMonth->modify('+1 month');
                        $nextMonth->modify('first day of this month');
                    ?>
                        <input type="hidden" name="set_data" value="<?=$nextMonth->format('Y-m-d')?>">
                        <button class="btn-nav-month"> <i class="fa fa-chevron-right"></i></button>
                    </form>
                </h3>
                <a href="index.php?set_data=<?=$data->format('Y-m-d')?>&notifications" class="notifications me-4 mt-1">
                <?php
                    $sqlFriendsInvite ="SELECT
                                            id
                                        FROM 
                                            `friends`
                                        WHERE
                                            friend = $id_usuario 
                                        AND status_cadastro = 0
                                        UNION ALL
                                        SELECT
                                            id
                                        FROM 
                                            `tasks_shared`
                                        WHERE
                                            id_user_shared = $id_usuario 
                                        AND status_cadastro = 0;";
                    $resultFriendsInvite = $PDO->query($sqlFriendsInvite);
                ?>
                    <i class="fa fa-bell" aria-hidden="true"></i>
                    <div id="notifications-count"><?=$resultFriendsInvite->rowCount()?></div>
                </a>
            </div>
        </section>
    </nav>
