<?php
    require_once('../common/ini.php');

    $errMsg = '';
    $result = '';

    // リロード時の従業員名＆カード番号保持処理
    if(isset($_POST['staff_name'])){
        if($_POST['staff_name'] != ''){
            $staff_name = $_POST['staff_name'];
        }else{
            $staff_name = '';
        }
    }else{
        $staff_name = '';
    }
    if(isset($_POST['card_num'])){
        if($_POST['card_num'] != ''){
            $card_num = $_POST['card_num'];
        }else{
            $card_num = '';
        }
    }else{
        $card_num = '';
    }

    // カード情報読み取りpythonプログラムの呼び出し
    if(isset($_POST['nfcRead'])){
        $command="/usr/local/bin/python3 ./nfcRead.py";
        exec($command,$output);
        sleep(1);
        if(empty($output)){
            $errMsg = 'カードの読み取りに失敗しました。';
        }else{
            $result = $output[0];
        }
    }

    // DB登録処理
    if(isset($_POST['regist'])){
        if($_POST['staff_name'] == "" || $_POST['card_num'] == "" ){
            $errMsg = '空欄があります。';
        }else{
            $regist_staff_name = $_POST['staff_name'];
            $regist_staff_name = str_replace(" ", "", $regist_staff_name);
            $regist_staff_name = str_replace("　", "", $regist_staff_name);
            // DBへの接続
            $dbh = new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            // SQL文の発行及びレコードの取得
            $sql = 'SELECT card_num from card where card_num = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$_POST['card_num']]);
            $rec = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            // DB切断
            $dbh = null;

            if(count($rec) != 0){
                echo 'このカードはすでに登録されています。';
            }else{
                // DBへの接続
                $dbh = new PDO($dsn,$user,$password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                // SQL文の発行及びレコードの取得
                $sql = 'SELECT staff_name FROM staff WHERE staff_name LIKE ?';
                $stmt = $dbh->prepare($sql);
                $stmt->execute(['%'.$regist_staff_name.'%']);
                $rec = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                // DB切断
                $dbh = null;

                if(count($rec) != 0){
                    echo 'この従業員名はすでに登録されています。';
                }else{
                    // DBへの接続
                    $dbh = new PDO($dsn,$user,$password);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                    // SQL文の発行及びレコードへの追加
                    $sql = 'INSERT INTO staff(staff_name) VALUES(?)';
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute([$regist_staff_name]);
                    $id = $dbh->lastInsertId();
                    // DB切断
                    $dbh = null;

                    $dbh = new PDO($dsn,$user,$password);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                    // SQL文の発行及びレコードへの追加
                    $sql = 'INSERT INTO card(staff_id, card_num) VALUES(?,?)';
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute([$id,$_POST['card_num']]);
                    $id = $dbh->lastInsertId();
                    // DB切断
                    $dbh = null;

                    echo '登録しました';
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>勤怠管理システム｜従業員登録</title>
    </head>
    <body>
        <a href="../index.php"><button>トップページ</button></a>
        <a href="staff_all.php"><button>従業員一覧</button></a>

        <p>従業員登録</p>
        
        <form action="" method="POST">
            <?php echo '<p style="color:red;">'.$errMsg.'</p>' ?>
            <label for="staff_name">名前：</label>
            <input type="text" name="staff_name" value="<?php echo $staff_name ?>">
            <br>
            <label for="card_num">IC　：</label>
            <input type="text" name="card_num" value="<?php echo $result ?>" >
            <button type="submit" name="nfcRead">カード読み取り</button><br>
            <button type="submit" name="regist">登録</button>
        </form>
    </body>
</html>