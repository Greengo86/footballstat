<?php

//echo debug($own_Goal);
//echo debug($table);
//Делим массив на части по "7" команд для того, чтобы разместить в виджет
//var_dump($table);
$part = array_chunk($table, 7, 1);

?>

<div class="col-md-4">
    <div class="intro-table">
        <h5 class="white heading text-center"><?php echo $champ ?></h5>
        <div class="owl-testimonials bottom">
            <!--    Выводим в цикле($part[0], $part[1], $part[2],$part[n],) последовательно части ранее разбитого массива в разные части слайдера в таблицу, разделённого на части по 7 команд-->
            <?php foreach ($part as $key => $value1) : ?>
                <div class="item">
                    <table>
                        <thead>
                        <tr>
                            <th></th>
                            <th>Игры</th>
                            <th>Очки</th>
                            <th colspan="2">РМ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!--                        Выводим в таблицу Команду, кол-во игр, очки, разницу забитых и пропущенных мячей-->
                        <?php foreach ($part[$key] as $team => $value) :
                            echo "<tr>";
                            echo "<td>$team</td>";
                            echo "<td>$value[games]</td>";
                            echo "<td>$value[points]</td>";
                            echo "<td>$value[goal]</td>";
                            echo "<td>$value[ownGoal]</td>";
                            echo "</tr>"; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
