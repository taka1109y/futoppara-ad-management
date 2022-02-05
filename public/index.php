<?php
    require_once('./common/ini.php');

    // カレンターの日付設定
    if(isset($_GET['serch'])) {//カレンダーにて日付選択した場合
        $selectDate = $_GET['selectDate'];
        $date = $selectDate;
    }else{//遷移時及びパラメータリセット状態
        $date = new DateTime();
        $date = $date->format('Y-m-d');
    }

    // DBへの接続
    $dbh = new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    // SQL文の発行及びレコードへの追加
    $sql = 'select staff_name,datetime from kintai as k inner join staff as u on k.staff_id = u.id where k.datetime like ? order by datetime asc';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$date.'%']);

    // DB切断
    $dbh = null;
    
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
        <a href="staff/staff_add.php"><button>従業員登録</button></a>
        <a href="staff/staff_all.php"><button>従業員一覧</button></a>

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
                while(true){
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);// $stmtから１レコード取り出し
                    if($rec == false){
                        break;
                    }
                    $html = '';
                    $html .= '<tr>';
                    $html .= '<td>'.$rec['staff_name'].'</td>';
                    $html .= '<td>'.$rec['datetime'].'</td>';
                    $html .= '</tr>';
                    print $html;
                }
            ?>
        </table>
    </body>
</html>