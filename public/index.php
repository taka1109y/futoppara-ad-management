<?php
    include 'db/db.php';

    // カレンターの日付設定
    if(isset($_GET['serch'])) {
        $selectDate = $_GET['selectDate'];
        $date = $selectDate;
    }else{
        $date = new DateTime();
        $date = $date->format('Y-m-d');
    }
?>

<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>勤怠管理システム</title>

        <style>
            table,th,td{
                padding: 5px;
                border-collapse: collapse;
                border: 1px solid;
            }
            table{
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <a href="user/user_add.php"><button>従業員登録</button></a>
        <a href="user/user_all.php"><button>従業員一覧</button></a>

        <p>ログ一覧</p>

        <form action="" method="GET">
            <input type="date" name="selectDate" value="<?php echo $date ?>">
            <button type="submit" name="serch">実行</button>
            <a href="index.php"><button type="button">リセット</button></a>
        </form>
        
        <table>
        <tr>
            <th>従業員名</th>
            <th>時刻</th>
        </tr>
        
            <?php
            // ログ一覧取得処理
            $query = $pdo->prepare('select user_name,datatime 
                                    from kintai as k 
                                    inner join user as u on k.user_id = u.id 
                                    where k.datatime like ? order by datatime asc');
            $query->execute([$date.'%']);
            foreach($query->fetchAll() as $val){
                // $bday = new DateTime($val['datatime']);
                // echo $bday->format('Y');
                echo '<tr><td>'.$val['user_name'].'</td>';
                echo '<td>'.$val['datatime'].'</td></tr>';
                
            }
            ?>
        
        </table>
        
    </body>
</html>