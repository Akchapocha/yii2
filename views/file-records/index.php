<?php

use yii\helpers\Html;

?>

<div class="body-content">

    <div class="container records">

        <?php if (isset($directories)):?>

            <?php if (isset($directories[0])):?>

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <td>Имя папки</td>
                            <td>Последние изменения</td>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($directories as $item => $value):?>

                            <tr id="<?= $item?>" name="<?= $value[0]?>" title="Перейти к просмотру">

                                <td><?= $value[0]?></td>
                                <td><?= $value[1]?></td>

                            </tr>

                        <?php endforeach;?>

                    </tbody>

                </table>

            <?php else:?>

                <?= $directories?>

            <?php endif;?>

        <?php endif;?>


    </div>

</div>
