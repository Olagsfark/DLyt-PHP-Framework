<!DOCTYPE html>
<html>
    <head>
        <?php $data = DGetViewContext('test'); ?>
        <?= $data['headData'] ?>
    </head>
    <body>
        <h1><?= $data['name'] ?></h1>
        <blockquote><?= $data['text'] ?></blockquote>
        <code>
            <?= $data['test'] ?>
        </code>
    </body>

