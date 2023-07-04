<?php

use yii\helpers\Html;
use yii\helpers\Url;

//debug($onePage,1);

?>

<?php if ( isset($onePage) ):?>

    <?php if ( isset($onePage[0]) ):?>

        <a id="del" href="/file-records">Назад к списку папок</a>

        <h4 id="nameDir">Текущая папка: <?= $nameDir?></h4>

        <nav aria-label="Page navigation" class="container-fluid pagNav">

            <ul class="pagination">

                <?php if ( $numOfPage < $pagStrCount ):?>

                    <li class="disabled">
                        <a aria-label="Previous" class="pointer">
                            <span aria-hidden="true">&laquo; предыдущие 10</span>
                        </a>
                    </li>

                <?php else:?>

                    <li>
                        <a aria-label="Previous" onclick="plus_minus('-10') "class="pointer">
                            <span aria-hidden="true">&laquo; предыдущие 10</span>
                        </a>
                    </li>

                <?php endif;?>

                <?php foreach ($arrNumStr as $item => $value):?>

                    <?php if ($value == $numOfPage):?>

                        <li class="active"><a onclick="changePage('<?= $value?>')"><?= $value?></a></li>

                    <?php else:?>

                        <li><a class="pointer" onclick="changePage('<?= $value?>')"><?= $value?></a></li>

                    <?php endif;?>

                <?php endforeach;?>

                <?php if ( ($countPages - $numOfPage) < $pagStrCount ):?>

                    <li class="disabled">
                        <a aria-label="Next" class="pointer">
                            <span aria-hidden="true">следующие 10 &raquo;</span>
                        </a>
                    </li>

                <?php else:?>

                    <li>
                        <a aria-label="Next" onclick="plus_minus('+10')" class="pointer">
                            <span aria-hidden="true">следующие 10 &raquo;</span>
                        </a>
                    </li>

                <?php endif;?>



            </ul>

        </nav>

        <div class="container-fluid countPag">
            <label>Всего страниц:

                <?php if ( $countPages == 0 ):?>
                    1
                <?php else:?>
                    <?= $countPages?>
                <?php endif;?>

            </label>

            <label>Выберите номер страницы

                <select name="numOfPage" onchange="changeNumStr('<?= $nameDir?>')">

                    <?php if (intval($countPages) === 0):?>

                            <option selected>1</option>

                    <?php else:?>

                            <?php for ($i = 1; $i <= $countPages; $i++):?>

                                <?php if ($i == $numOfPage):?>

                                    <option selected><?= $i?></option>

                                <?php else:?>

                                    <option><?= $i?></option>

                                <?php endif;?>

                            <?php endfor;?>

                    <?php endif;?>

                </select>

            </label>

            <label>Записей на странице

                <select name="strOnPage" onchange="changeStrOnPage()">

                    <?php foreach ($rangeStrOnPage as $item => $value):?>

                        <?php if ($value == $strOnPage):?>

                            <option selected><?= $value?></option>

                        <?php else:?>

                            <option><?= $value?></option>

                        <?php endif;?>

                    <?php endforeach;?>

                </select>

            </label>

        </div>

        <table class="table table-bordered table-hover">

            <thead>

                <tr>

                    <td>Номер по списку</td>
                    <td>Имя файла</td>
                    <td>Запись</td>

                </tr>

            </thead>

            <tbody>

                <?php $firstRow = ( $strOnPage * ( $numOfPage - 1 ) ) + 1;?>
                <?php foreach ($onePage as $item => $value):?>

                    <tr id="<?= $item?>" name="<?= $value['name']?>">

                        <td><?= $firstRow?></td>
                        <td><?= $value['name']?></td>
                        <td>

                            <audio controls="">

                                <source src="<?= Url::to($value['src']);?>">
                                <a href="<?= Url::to($value['src']);?>">Скачать</a>

                            </audio>

                        </td>

                    </tr>

                <?php $firstRow++;?>
                <?php endforeach;?>

            </tbody>

        </table>

    <?php else:?>

        <?= $onePage?>

    <?php endif;?>

<?php endif;?>
