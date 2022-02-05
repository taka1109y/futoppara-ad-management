<?php
    require_once('../common/ini.php');

    // DBへの接続
    $dbh = new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    // SQL文の発行及びレコードへの追加
    $sql = 'select id,staff_name from staff';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // DB切断
    $dbh = null;
?>

<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>勤怠管理システム｜従業員一覧</title>

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
        <a href="staff_add.php"><button>従業員登録</button></a>

        <p>従業員一覧</p>
        
        <table>
        <tr>
            <th>従業員No.</th>
            <th>従業員名</th>
        </tr>
        <tr>
            <?php 
                while(true){
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);// $stmtから１レコード取り出し
                    if($rec == false){
                        break;
                    }
                    print '<tr><td>'.$rec['id'].'</td>';
                    print '<td>'.$rec['staff_name'].'</td></tr>';
                }
            ?>
        </tr>
        </table>
        
    </body>
</html>