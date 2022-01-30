<?php
    include "../common/ini.php";
?>

<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>勤怠管理システム｜ユーザー一覧</title>

        <style>
            table,th,td{
                padding: 5px;
                border-collapse: collapse;
                border: 1px solid;
            }
        </style>
    </head>
    <body>
        <a href="../index.php"><button>トップページ</button></a>
        <a href="user_all.php"><button>従業員登録</button></a>

        <p>従業員一覧</p>
        
        <table>
        <tr>
            <th>従業員No.</th>
            <th>従業員名</th>
        </tr>
        <tr>
            <?php 
                $query = $pdo->prepare('select id,user_name from user');
                $query->execute();
                foreach($query->fetchAll() as $val){
                    echo '<tr><td>'.$val['id'].'</td>';
                    echo '<td>'.$val['user_name'].'</td></tr>';
                }
            ?>
        </tr>
        </table>
        
    </body>
</html>