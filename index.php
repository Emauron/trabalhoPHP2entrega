<?php 
date_default_timezone_set('America/Sao_Paulo');
include 'conn.php'; 
session_start();

$id_usuario = (isset($_SESSION['id']) && $_SESSION['id'] != NULL) ? $_SESSION['id'] : header ('location: login_register.php') ;
$nome = (isset($_SESSION['nome']) && $_SESSION['nome'] != NULL) ? $_SESSION['nome'] : '' ;
//print_r($_REQUEST);
$set_data = (isset($_REQUEST['set_data']) && $_REQUEST['set_data'] != NULL) ? $_REQUEST['set_data'] : '';

include 'acoes.php'; 

if($set_data == ''){
    $data = new DateTime();  
    $_SESSION['set_data'] = $data->format('Y-m-d');
}else{
    $data = new DateTime(date($set_data));
    $_SESSION['set_data'] = $set_data;
}

include 'menu.php';

$current_data = new DateTime(); 
$current_data = $current_data->format('Y-m-d');

$month = $data->format('m');
$day = $data->format('d');
$dayWeek = $data->format('w');

$year = $data->format('Y');

$firstDmonth = clone $data;
$firstDmonth->modify('first day of this month');
$lastDmonth = clone $data;
$lastDmonth->modify('last day of this month');

$daysMonth = [];

for ($loadDay = clone $firstDmonth; $loadDay <= $lastDmonth; $loadDay->modify('+1 day')) {
    $dayOfMonth = $loadDay->format('j') ; 
    $firstDayWeekDay = $firstDmonth->format('w'); 
    $weekOfMonth = (($dayOfMonth + $firstDayWeekDay - 1) / 7) + 1; 
    // if (!isset($daysMonth[$weekOfMonth])) {
    //     $daysMonth[$weekOfMonth] = []; 
    // }
    $dayOfWeek =  $loadDay->format('w'); 
    $daysMonth[$weekOfMonth][$dayOfWeek] = "$dayOfWeek"; 
}
function convertIntDay($num){
    switch($num){
        case 0:
            return  "domingo";
        case 1:
            return  "segunda";
            break;
        case 2:
            return  "terça";
            break;
        case 3:
            return  "quarta";
            break;
        case 4:
            return "quinta";
            break;
        case 5:
            return  "sexta";
            break;
        case 6:
            return  "sabado";
            break;
        default:
            echo 'error';
    }
}
$dayWeek = convertIntDay($data->format('w'));
function convertIntMonth($num){
    switch($num){
        case '1':
            return  "Janeiro";
            break;
        case '2':
            return  "Fevereiro";
            break;
        case '3':
            return  "Maio";
            break;
        case '4':
            return  "Abril";
            break;
        case '5':
            return  "Março";
            break;
        case '6':
            return  "Junho";
            break;
        case '7':
            return  "Julho";
            break;
        case '8':
            return  "Agosto";
            break;
        case '9':
            return  "Setembro";
            break;
        case '10':
            return  "Outubro";
            break;
        case '11':
            return  "Novembro";
            break;
        case '12':
            return  "Dezembro";
            break;
        default :
            
    }
}
?>
<section class="row">
    <?php include 'menu_lateral.php'; ?>
    <div class="col col-md-9" style="padding:0;">
        <table class="table table-dark">
            <thead>
                <tr>
                    <td class="text-center bg-dark" colspan=7>
                        <h4><strong><?=mb_strtoupper(convertIntMonth($month), 'UTF-8');?></strong></h4>
                    </td>
                </tr>
                <tr>
                <?php
                    for($i = 0; $i < 7; $i++){
                        $templateDay = convertIntDay($i);
                ?>
                    <td class="text-center">
                        <h5><strong><?=mb_strtoupper($templateDay, 'UTF-8');?></strong></h5>
                    </td>
                <?php
                    }
                ?>
                </tr>
            </thead>
            <tbody>
            <?php
                $firstDmonth     = $firstDmonth->format('Y-m-d');
                $lastDmonth      = $lastDmonth->format('Y-m-d');
                $sqlFeriado      = "SELECT `feriados`.feriado, `feriados`.data FROM `feriados` WHERE `feriados`.data > '$firstDmonth' AND `feriados`.data < '$lastDmonth' ORDER BY data ASC;";
                $resultFeriado   = $PDO->query($sqlFeriado);
                $numFeriados     = $resultFeriado->rowCount();
                $datasFeriados   = [];
                $nomesFeriados   = [];
                if($numFeriados > 0){
                    $feriados = [];
                    while ($consultaFeriado = $resultFeriado->fetch(PDO::FETCH_OBJ)) {
                        $feriado = [
                            'nome' => $consultaFeriado->feriado,
                            'data' => $consultaFeriado->data
                        ];
                        $feriados[] = $feriado;
                        $datasFeriados = array_column($feriados, 'data');
                        $nomesFeriados[$consultaFeriado->data] = $consultaFeriado->feriado;
                    }
                }
                $contDays = 1;
                for($i = 1; $i <= 6; $i++){
            ?>
                    <tr>
                    <?php
                        for($y = 0; $y <= 6; $y++){
                            $dataByDay = $year . '-' . $month . '-' . '' . (($contDays < 10 ) ? '0' .  $contDays: $contDays);
                    ?>
                            <td 
                                class="text-center 
                                <?php
                                    if($contDays == $day && isset($daysMonth[$i][$y]) && $daysMonth[$i][$y] == $y) {
                                        echo 'bg-info';
                                    }else if($numFeriados > 0 && in_array($dataByDay, $datasFeriados)){
                                        echo 'bg-primary';
                                    }
                                ?>">
                                <a class="text-decoration-none text-white" href="index.php?set_data=<?=$dataByDay?>">
                                    <div class="cell d-flex justify-content-center align-content-center flex-column">
                                    <?php
                                        if(isset($daysMonth[$i][$y]) && $daysMonth[$i][$y] == $y){
                                            echo "<h5>$contDays</h5>";
                                            $contDays++;
                                    ?> 
                                            <div class="tasks-list-day-item">
                                                <ul class="tasks-list-day d-flex flex-column">
                                                    <?php
                                                        $sqlTasksCalendar ="SELECT
                                                                                `tasks`.id,
                                                                                `tasks`.name,
                                                                                `tasks`.task,
                                                                                `tasks`.data_task,
                                                                                `tasks`.status_cadastro
                                                                            FROM
                                                                                tasks
                                                                            WHERE
                                                                                data_task = '$dataByDay' 
                                                                            AND id_usuario = $id_usuario
                                                                            UNION ALL
                                                                            SELECT
                                                                                `tasks`.id,
                                                                                `tasks`.name,
                                                                                `tasks`.task,
                                                                                `tasks`.data_task,
                                                                                `tasks_shared`.status_cadastro
                                                                            FROM
                                                                                tasks
                                                                            RIGHT JOIN 
                                                                                `tasks_shared`
                                                                            ON
                                                                                `tasks_shared`.id_task = `tasks`.id
                                                                            WHERE 
                                                                                `tasks_shared`.id_user_shared = $id_usuario
                                                                            AND `tasks`.data_task = '$dataByDay'
                                                                            AND `tasks_shared`.status_cadastro  = 1;";
                                                        $resultTasksCalendar = $PDO->query($sqlTasksCalendar);
                                                        while($consultaTaksCalendar = $resultTasksCalendar->fetch(PDO::FETCH_OBJ)){
                                                            if($consultaTaksCalendar->status_cadastro == 0){    
                                                                if($consultaTaksCalendar->data_task < $current_data){
                                                                    $status = 'danger';
                                                                }else if($consultaTaksCalendar->data_task == $current_data){
                                                                    $status = 'warning';
                                                                }else if($consultaTaksCalendar->data_task > $current_data){
                                                                    $status = 'primary';
                                                                }
                                                            }else {
                                                                $status = 'success';
                                                            }
                                                    ?>
                                                            <li class="bg-<?=$status?> mb-1">
                                                                <?=$consultaTaksCalendar->name?>
                                                            </li>
                                                    <?php
                                                        }
                                                    ?>
                                                </ul>
                                            <?php
                                                if($numFeriados > 0 && in_array($dataByDay, $datasFeriados)){
                                                    echo $nomesFeriados[$dataByDay];
                                                }
                                            ?>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                    </div>
                                </a>
                            </td>
                    <?php
                        }
                    ?>
                    </tr>
            <?php
                }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan = 7>
                        <h4><strong><?=$year?></strong></h4>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
