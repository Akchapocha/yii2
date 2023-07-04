<?php

use yii\helpers\Html;
use yii\helpers\Url;

$url = Yii::$app->request->url;

?>

<nav class="navbar navbar-default operators">


    <div class="container-fluid">

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav">

                <?php if ($pages !== []):?>

                    <?php foreach ($pages as $item => $page):?>

                        <?php
                        if ( intval($page['id']) === 20) {
                            $page['name_button'] = 'Статистика';
                        }
                        ?>

                        <?php if ( intval($pages[$item]['id']) === 26 ):?>

                            <li id="li">Операторы:</li>

                        <?php endif;?>


                        <?php if ( intval($page['id']) === 23 ):?>

                            <li class="active"><?= Html::a($page['name_button'], Url::to($page['src_page']))?></li>

                        <?php elseif($page['src_page'] !== '/operators-hidden'):?>

                            <li><?= Html::a($page['name_button'], Url::to($page['src_page']))?></li>

                        <?php endif;?>

                    <?php endforeach;?>

                <?php endif;?>

            </ul>

        </div>

    </div>

</nav>

<div class="body-content">

    <div class="container">

        <table class="table  table-bordered table-hover list-operators">

            <caption>

                <?php
                if ($operators !== []){
                    $countOperators = count($operators);
                } else {
                    $countOperators = '';
                }
                ?>

                <p>Скрытых операторов: <?= $countOperators;?></p>
                <p ><a href="/operators-visible">Посмотреть видимых</a></p>
                <p id="createOperator">добавить</p>

            </caption>

            <thead>
            <th>№</th>
            <th>фио</th>
            <th>пин</th>
            <th>очередь 1</th>
            <th>очередь 2</th>
            <th>очередь 3</th>
            <th>удалить</th>
            </thead>

            <?php if ($operators !== []):?>

                <?php foreach ($operators as $item => $operator):?>

                    <?= Html::beginTag('tr', ['id' => $operator['id']])?>

                    <td><?= $item+1;?></td>
                    <td><?= $operator['agent_name']?></td>
                    <td><?= $operator['pin']?></td>
                    <td><?= $operator['q1']?></td>
                    <td><?= $operator['q2']?></td>
                    <td><?= $operator['q3']?></td>
                    <td><span id="delete" class="glyphicon glyphicon-remove" aria-hidden="true" title="Удалить совсем"></span></td>

                    <?= Html::endTag('tr')?>

                <?php endforeach;?>

            <?php endif;?>

        </table>

        <div class="modal-overlay" id="modal-overlay"></div>

        <div class="modal" id="modal" aria-hidden="true" aria-labelledby="modalTitle" aria-describedby="modalDescription" role="dialog">

            <span class="glyphicon glyphicon-remove closeModal" aria-hidden="true"></span>

            <div class="modal-guts" role="document">

                <h1>Добавление оператора</h1>

                <ul>
                    <li>
                        <label>фио:</label>
                        <input type="text" name="fio" required>
                    </li>
                    <li>
                        <label>пин:</label>
                        <input type="text" name="pin" pattern="[0-9]{4}" title="4 цифры" disabled>
                        <button type="button" name="changePin" title="Изменить PIN в ручную">изменить ПИН</button>
                    </li>
                    <li>
                        <label>очередь 1:</label>
                        <input type="text" name="queue1" pattern="[0-9]{4}" title="4 цифры">
                    </li>
                    <li>
                        <label>очередь 2:</label>
                        <input type="text" name="queue2" pattern="[0-9]{4}" title="4 цифры">
                    </li>
                    <li>
                        <label>очередь 3:</label>
                        <input type="text" name="queue3" pattern="[0-9]{4}" title="4 цифры">
                    </li>
                </ul>

                <button type="button" id="save">Сохранить</button>
                <button type="button" id="cancel">Отменить</button>

            </div>

        </div>

    </div>

</div>

