<?php


$this->title = $champ . '|Лучшие Бомбардиры и Ассистенты: '; ?>

<h2 class="text-center">Лучшие Бомбардиры и Ассистенты: <?= $champ ?></h2>

<div class="container">
    <div class="row">
        <div id="scorers">
            <div class="table-wrap">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th colspan="1">Бомбардиры</th>
                            <th colspan="1">Забито</th>
                            <th colspan="1">С пенальти</th>
                            <th colspan="1">Сыграно матчей</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($scorer['score'] as $value) : ?>
                            <?php echo "<tr>"; ?>
                            <?php echo "<td>$value[0]</td>"; ?>
                            <?php echo "<td>$value[1]</td>"; ?>
                            <?php echo "<td>$value[2]</td>"; ?>
                            <?php echo "<td>$value[3]</td>"; ?>
                            <?php echo "</tr>"; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="scorers">
            <div class="table-wrap">
                <div class="table-responsive">
                    <table class="table assist">
                        <thead>
                        <tr>
                            <th colspan="1">Ассистенты</th>
                            <th colspan="1">Передач</th>
                            <th colspan="1">Сыграно матчей</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($scorer['assist'] as $value) : ?>
                            <?php echo "<tr>"; ?>
                            <?php echo "<td>$value[0]</td>"; ?>
                            <?php echo "<td>$value[1]</td>"; ?>
                            <?php echo "<td>$value[2]</td>"; ?>
                            <?php echo "</tr>"; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
