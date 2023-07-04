<?php

use yii\helpers\Html;

?>
<div class="body-content it">

    <?= Html::beginForm()?>

<!------Установка прав для групп пользователей------------------------------------------------------------------------->
        <div class="container container-group">
            <ul class="user-group" name="groupsCheck">

                <?php foreach ($groups as $group):?>

                    <?= Html::beginTag('li', ['class' => 'list-group-item group-it',
                                                    'title' => $group['description']])?>

                        <?= $group['name_group']?>

                        <?= Html::tag('input', '', [
                            'name' => $group['name_group'],
                            'type' => 'checkbox',
                            ])?>

                    <?= Html::endTag('li')?>

                <?php endforeach;?>
            </ul>

            <ul class="user-group padding-right" name="groupsRadio">

                <?php foreach ($pages as $page):?>

                    <?= Html::beginTag('li', ['class' => 'list-group-item group-it'])?>

                        <?= $page['name_button']?>

                        <?= Html::tag('input', '', [
                                'name' => 'group ' . $page['name_button'],
                                'type' => 'radio',
                                'value' => 'read',
                                'title' => 'Разрешить чтение'])?>

                        <?= Html::tag('input', '', [
                                'name' => 'group ' . $page['name_button'],
                                'type' => 'radio',
                                'value' => 'block',
                                'title' => 'Заблокировать'])?>

                    <?= Html::endTag('li')?>

                <?php endforeach;?>

                <?= Html::tag('button', 'Применить правила', ['type' => 'button',
                                                                            'class' => 'btn btn-default btn-color',
                                                                            'name' => 'applyGroup'])?>

            </ul>
        </div>

<!------Установка прав пользователей---------------------------------------------------------------------------------->
        <div class="container container-users">

            <ul class="user-group find-user">

                <?= Html::tag('input', '', [
                    'type' => 'text',
                    'name' => 'findUser',
                    'placeholder' => 'Данные для поиска',
                    'aria-describedby' => 'basic-addon2',
                    'class' => 'form-control'])?>

                <?= Html::tag('button', 'Поиск', [
                        'type' => 'button',
                        'name' => 'find',
                        'class' => 'btn btn-default'])?>

                <?= Html::beginTag('select', [
                        'multiple' => 'multiple',
                        'name' => 'users'])?>

                <?= Html::endTag('select')?>

            </ul>

            <ul class="user-group padding-right" name="usersRadio">

                <?php foreach ($pages as $page):?>

                    <?= Html::beginTag('li', ['class' => 'list-group-item group-it'])?>

                    <?= $page['name_button']?>

                    <?= Html::tag('input', '', [
                        'name' => 'user ' . $page['name_button'],
                        'type' => 'radio',
                        'value' => 'read',
                        'title' => 'Разрешить чтение'])?>

                    <?= Html::tag('input', '', [
                        'name' => 'user ' . $page['name_button'],
                        'type' => 'radio',
                        'value' => 'block',
                        'title' => 'Заблокировать'])?>

                    <?= Html::endTag('li')?>

                <?php endforeach;?>

                <?= Html::tag('button', 'Применить правила', ['type' => 'button', 'class' => 'btn btn-default btn-color', 'name' => 'applyUser'])?>

            </ul>

        </div>

    <?= Html::endForm()?>

</div>
