<!DOCTYPE html>
<html>
    <head>
        <?php $data = DGetViewContext('home'); ?>
        <?= $data['headData'] ?>
    </head>
    <body>
        <h1><?= $data['name'] ?></h1>
        <blockquote><?= $data['text'] ?></blockquote>
        <code>
            <?= print($data['test']) ?>
        </code>
    </body>
</html>