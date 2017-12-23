<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php
$dbname = 'station';        // データベース名
$host = 'localhost';        // ホスト
$user = 'root';         // mysqlに接続するユーザー
$password = '1321083';    // パスワード
$dns = 'mysql:dbname='.$dbname.';host='.$host.';charset=utf8';

try {
    $dbh = new PDO($dns, $user, $password,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    if ($dbh == null) {
        print_r('接続失敗').PHP_EOL;
    } else {
        print_r('').PHP_EOL;
    }
} catch(PDOException $e) {
    echo('Connection failed:'.$e->getMessage());
    die();
}

$sql = 'SHOW TABLES';
$stmt = $dbh->query($sql);

while ($result = $stmt->fetch(PDO::FETCH_NUM)){
    $table_names[] = $result[0];
}

$table_data = array();
foreach ($table_names as $key => $val) {
    $sql2 = "SELECT * FROM $val;";
    $stmt2 = $dbh->query($sql2);
    $table_data[$val] = array();
    while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        foreach ($result2 as $key2 => $val2) {
            $table_data[$val][$key2] = $val2;
        }
    }
}
$count=0;
foreach ($table_data as $key => $val) {
    $count+=1;
    echo "<h1>$key</h1>";
    if (empty($val)) {
        continue;
    }
    echo "<table id=$count onclick=clicked(this) border=1 style=border-collapse:collapse;>";
    echo "<tr>";
    foreach ($table_data[$key] as $key2 => $val2) {
    echo "<th>";
    echo $key2;
    echo "</th>";
    }
    echo "</tr>";
    echo "<tr>";
    foreach ($table_data[$key] as $key2 => $val2) {
    echo "<td>";
    echo $val2;
    echo "</td>";
    }
    echo "</tr>";
    echo "</table>";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
<title>Google Map API v3-10</title>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/superagent/3.8.2/superagent.js" charset="utf-8"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfQAmY0OhNBefIlWgpXOQb2uuZkXqK7HA" charset="utf-8"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
var request = window.superagent;
function clicked(table){
  var table = document.getElementById(table.id);
  var start = table.rows[1].cells[0].firstChild.data;
  var goal = table.rows[1].cells[3].firstChild.data;
  var form1 = document.getElementById("origin");
  var form2 = document.getElementById("destination");
  form1.setAttribute("value", start);
  form2.setAttribute("value", goal);
  // console.log(td1,td2);
}
var directions; //ルートのインスタンス
var directionRenderer; //ルートレンダラ
var map;      //マップのインスタンス
var ds = google.maps.DirectionsStatus;//ルート結果のステータス
var directionsErr = new Array(); //ルート結果のエラーメッセージ
directionsErr[ds.INVALID_REQUEST] = "指定された DirectionsRequest が無効です。";
directionsErr[ds.MAX_WAYPOINTS_EXCEEDED] = "DirectionsRequest に指定された DirectionsWaypoint が多すぎます。ウェイポイントの最大許容数は 8 に出発地点と到着地点を加えた数です。";
directionsErr[ds.NOT_FOUND] = "出発地点、到着地点、ウェイポイントのうち、少なくとも 1 つがジオコード化できませんでした。";
directionsErr[ds.OVER_QUERY_LIMIT] = "ウェブページは、短期間にリクエストの制限回数を超えました。";
directionsErr[ds.REQUEST_DENIED] = "ウェブページではルート サービスを使用できません。";
directionsErr[ds.UNKNOWN_ERROR] = "サーバー エラーのため、ルート リクエストを処理できませんでした。もう一度試すと正常に処理される可能性があります。";
directionsErr[ds.ZERO_RESULTS] = "出発地点と到着地点間でルートを見つけられませんでした。";

// Onload時処理
function initialize() {
    // ルートの生成
    directions = new google.maps.DirectionsService();
    // ルートレンダラの生成
    directionRenderer = new google.maps.DirectionsRenderer();
    // Google Mapで利用する初期設定用の変数
    var mapOptions = {
        zoom: 17,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: new google.maps.LatLng(35.486519, 139.341126)
    };
    // GoogleMapの生成
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    // ルートレンダラにマップを関連付ける
    directionRenderer.setMap(map);
}
// [検索]ボタン処理
function searchRoute() {

    // テキストボックスから検索の出発・到着を取得
    var origin = document.getElementById("origin").value;
    var destination = document.getElementById("destination").value;
    var weight = document.getElementById("weight").value;
    // ルート検索を依頼する
    directions.route(
        { // ルート リクエスト
          'origin'     : origin,     //出発地点
          'destination': destination,//到着地点
          'travelMode' : google.maps.DirectionsTravelMode.WALKING //ルートタイプ:徒歩
        },
        function(results, status)
        { // ルート結果callback関数
          if (status == ds.OK)
          {  // 結果がOK ??
            // 結果をレンダラに渡し、ルートをマップに表示
            directionRenderer.setDirections(results);
            SetDistance(results,weight);
          } else {
              // 結果がOKではない場合
              alert("ルート検索が失敗しました。理由: " + directionsErr[status]);
          }
        });
}
    // 移動距離を設定
        function SetDistance(routeData,weight)
        {
            console.log(weight);
            var distance = GetDistanceKm(routeData.routes[0].legs);
            $("#distance").text(distance);
            //$("#momentun").text(distance/4.8*weight*1.05*3);
            let  w = distance/4.8*weight*1.05*3;
            let  r =Math.round(w);
            $("#momentun").text(r);
        }


        // 距離を取得
        function GetDistanceKm(legs)
        {
            var journey = 0;
            for (var i in legs)
            {
                journey += legs[i].distance.value;
            }
            return journey / 1000;
        }

        //CSVファイルを読み込む関数getCSV()の定義
        function getCSV()
        {
            var req = new XMLHttpRequest(); // HTTPでファイルを読み込むためのXMLHttpRrequestオブジェクトを生成
            req.open("get", "sample.csv", true); // アクセスするファイルを指定
            req.send(null); // HTTPリクエストの発行

            // レスポンスが返ってきたらconvertCSVtoArray()を呼ぶ
            req.onload = function(){
                convertCSVtoArray(req.responseText); // 渡されるのは読み込んだCSVデータ
                }
            }

            // 読み込んだCSVデータを二次元配列に変換する関数convertCSVtoArray()の定義
            function convertCSVtoArray(str){ // 読み込んだCSVデータが文字列として渡される
            var result = []; // 最終的な二次元配列を入れるための配列
            var tmp = str.split("\n"); // 改行を区切り文字として行を要素とした配列を生成

            // 各行ごとにカンマで区切った文字列を要素とした二次元配列を生成
            for(var i=0;i<tmp.length;++i){
                result[i] = tmp[i].split(',');
                }

                alert(result[1][2]); // 300yen
                }
                getCSV(); //最初に実行される

</script>
</head>
<body onload="initialize();">
  <div id="map" style="width: 800px; height: 500px; border: 1px solid Gray;"> </div>
  <div>
      <p>開始地点：<input id="origin" type="textbox" placeholder="開始地点を入力" value="" size="40" >  </p>
      <p>終了地点：<input id="destination" type="textbox" placeholder="終了地点を入力" value="" size="40" >  </p>
      <p>体重：<input id="weight" type="password" placeholder="体重を入力(kg)" value="" size="40" ></p>
    <input type="button"　 value="検索" onclick="searchRoute()">
  </div>
<span id="distance" style="font-size:36px;padding-right:10px;"></span>km
<motion id="momentun" style="font-size:36px;padding-right:10px;"></motion>kcal
</body>