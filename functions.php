<?php

function randomstr($length)
{
  $possible = "ABCDEFGHIJKJMNPQRSTUVWXYZ987654321";
  $str = "";
  while (strlen($str) < $length) {
    $str =   $str . substr($possible, (rand() % strlen($possible)), 1);
  }
  return $str;
}

function randomnumber($length)
{
  $possible = "0123456789";
  $str = "";
  while (strlen($str) < $length) {
    $str =   $str . substr($possible, (rand() % strlen($possible)), 1);
  }
  return $str;
}

function thai_date($datetime, $type = "1")
{
  $month1s = array("01"=>"มกราคม","02"=>"กุมภาพันธ์","03"=>"มีนาคม","04"=>"เมษายน","05"=>"พฤษภาคม","06"=>"มิถุนายน","07"=>"กรกฎาคม","08"=>"สิงหาคม","09"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
  $month2s = array("01"=>"ม.ค.","02"=>"ก.พ.","03"=>"ม.ค.","04"=>"เม.ย","05"=>"พ.ค.","06"=>"มิ.ย.","07"=>"ก.ค.","08"=>"ส.ค.","09"=>"ก.ย.","10"=>"ต.ค.","11"=>"พ.ย.","12"=>"ธ.ค.");

  list($date, $time) = explode(' ', $datetime); // แยกวันที่ กับ เวลาออกจากกัน
  list($Y, $m, $dd) = explode('-', $date); // แยกวันเป็น ปี เดือน วัน
  list($H, $i, $s) = explode(':', $time); // แยกเวลา ออกเป็น ชั่วโมง นาที วินาที
  $Y = $Y + 543; // เปลี่ยน ค.ศ. เป็น พ.ศ.

  $d = ($dd < 10)? substr($dd, 1, 1): $dd;

  switch ($type) {
    case '1':
        $m = $month1s[$m];
        $thaiDate = $d . ' ' . $m . ' ' . $Y; // 1 มกราคม 2566
        break;
    case '2':
        $m = $month2s[$m];
        $Y = substr($Y, -2);
        $thaiDate = $d . ' ' . $m . ' ' . $Y; // 1 ม.ค. 66
        break;
    case '3':
        $m = $month1s[$m];
        $thaiDate = $d . ' ' . $m . ' ' . $Y . ' เวลา ' . $H . ' นาฬิกา ' . $i . ' นาที ' . $s . ' วินาที'; // 1 มกราคม 2566 เวลา 18 นาฬิกา 45 นาที 30 วินาที
        break;
    case '4':
        $m = $month2s[$m];
        $Y = substr($Y, -2);
        $thaiDate = $d . ' ' . $m . ' ' . $Y . ' เวลา ' . $H . ':' . $i . ' น.'; // 1 ม.ค. 66 เวลา 18:45 น.
        break;
    case '5':
        $m = $month2s[$m];
        $Y = substr($Y, -2);
        $thaiDate = $dd . ' ' . $m . ' ' . $Y; // 01 ม.ค. 66
        break;
  }
  return $thaiDate;
}

function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d')
{
  $dates = array();
  $current = strtotime($first);
  $last = strtotime($last);
  while ($current <= $last) {
    $dates[] = date($output_format, $current);
    $current = strtotime($step, $current);
  }
  return $dates;
}

function thaibath($number)
{
  $txtnum1 = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ');
  $txtnum2 = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
  $number = str_replace(",", "", $number);
  $number = str_replace(" ", "", $number);
  $number = str_replace("บาท", "", $number);
  $number = explode(".", $number);
  if (sizeof($number) > 2) {
    return 'ทศนิยมหลายตัว';
    exit;
  }
  $strlen = strlen($number[0]);
  $convert = '';
  for ($i = 0; $i < $strlen; $i++) {
    $n = substr($number[0], $i, 1);
    if ($n != 0) {
      if ($i == ($strlen - 1) and $n == 1) {
        $convert .= 'เอ็ด';
      } elseif ($i == ($strlen - 2) and $n == 2) {
        $convert .= 'ยี่';
      } elseif ($i == ($strlen - 2) and $n == 1) {
        $convert .= '';
      } else {
        $convert .= $txtnum1[$n];
      }
      $convert .= $txtnum2[$strlen - $i - 1];
    }
  }

  $convert .= 'บาท';
  if (
    $number[1] == '0' or $number[1] == '00' or
    $number[1] == ''
  ) {
    $convert .= 'ถ้วน';
  } else {
    $strlen = strlen($number[1]);
    for ($i = 0; $i < $strlen; $i++) {
      $n = substr($number[1], $i, 1);
      if ($n != 0) {
        if ($i == ($strlen - 1) and $n == 1) {
          $convert
            .= 'เอ็ด';
        } elseif (
          $i == ($strlen - 2) and
          $n == 2
        ) {
          $convert .= 'ยี่';
        } elseif (
          $i == ($strlen - 2) and
          $n == 1
        ) {
          $convert .= '';
        } else {
          $convert .= $txtnum1[$n];
        }
        $convert .= $txtnum2[$strlen - $i - 1];
      }
    }
    $convert .= 'สตางค์';
  }
  return $convert;
}


?>
