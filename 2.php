<?php
  //변수 생성
  $tireqty = (int)$_POST['tireqty'];//Form변수
  $oilqty = (int)$_POST['oilqty'];//Form변수
  $sparkqty = (int)$_POST['sparkqty'];//Form변수
  //이 외에도 전송하는 방식에 따라 $_GET, $_REQUEST도 있다.
  $document_root = $_SERVER['DOCUMENT_ROOT'];
  $address = preg_replace('/\t|\R/',' ',$_POST['address']);
  $date = date('H:i, jS F Y');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Bob's Auto Parts - Order Form</title>
  </head>
  <body>
    <h1>Bob's Auto Parts</h1>
    <h2>Order Form</h2>
    <?php
          echo "<p>Order Processed at ".date('H:i, jS F Y')."</p>";
          echo "<p>Your Order is as follows: </p>";

          $totalqty = 0;
          $totalprice = 0.00;

          define('TIREPRICE', '100');
          define('OILPRICE', '10');
          define('SPARKPRICE', '4');

          echo htmlspecialchars($tireqty).' tires <br />';/* htmlspecialchars: 변수가 html에 사용되는
                                                            특수문자일 경우 html코드로 인식되는 것을 막기 위함*/
          echo htmlspecialchars($oilqty).' bottles of oil <br />'; //문자열 결합은 .으로 한다
          echo htmlspecialchars($sparkqty).' spark of plugs <br />';//PHP의 식별자는 대소문자 구별함

          $totalqty = $tireqty + $oilqty + $sparkqty;
          echo ('Items ordered: '.$totalqty.'<br />');

          $totalprice = $tireqty * TIREPRICE +
                        $oilqty * OILPRICE +
                        $sparkqty * SPARKPRICE;

          echo 'Subtotal: $'.number_format($totalprice,2).'<br />';

          $taxrate = 0.10;
          $totalprice = $totalprice * (1 + $taxrate);

          echo 'Total including tax: $'.number_format($totalprice,2).'<br />';
          echo "<p>Address to ship to is ".htmlspecialchars($address).'</p>';

          $outputdata = $date." ".$tireqty." tires  ".$oilqty."bottles of oil  "
                      .$sparkqty."spark of plugs ".$totalprice." ".$address."\t";

          //파일 열기
          $fp = fopen("$document_root/PHP/orders/orders.txt",'a');
          if (!$fp) {
            echo "<p><strong>Your order could not be processed at this time.
              Please try again later.</strong></p>";
              exit;
          }

          flock($fp, LOCK_EX);
          fwrite($fp, $outputdata);
          flock($fp, LOCK_UN);
          fclose($fp);

          echo "<p>Order written</p>";

    ?>
  </body>
</html>
