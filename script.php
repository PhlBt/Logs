<?php

switch ($_GET['action']) {
  case 'upload':
    $prefix = '_'.rand(1, 999);
    $file = $_FILES['file']['tmp_name'];
    $fileName = (!empty($_GET['file_name']))
      ? $_GET['file_name']
      : $_FILES['file']['name'].$prefix;

    $dom = new domDocument;

    $status = $dom->load($file);

    if ($status == true) {
      $arr = [];
      $fp = fopen('./csv/'.$fileName.'.csv', 'w');
      foreach ($dom->getElementsByTagName('item') as $key => $value) {
        $item = [
          'id' => $key,
          'url' => $value->getElementsByTagName('url')->item(0)->textContent,
          'path' => $value->getElementsByTagName('path')->item(0)->textContent,
          'status' => $value->getElementsByTagName('status')->item(0)->textContent,
        ];
        $arr[] = $item;
        fputcsv($fp, $item);
      }
      // $statusCSV = file_put_contents('./csv/'.$fileName.'.csv', $str, FILE_APPEND);
      $status = file_put_contents('./data/'.$fileName, json_encode($arr));
      fclose($fp);
      if ($status) {
        die(json_encode(['status' => true, 'message' => 'upload success', 'test' => [$str, $item]]));
      } else {
        die(json_encode(['status' => false, 'message' => 'upload failed']));
      }
    } else {
      die(json_encode(['status' => false, 'message' => 'dom failed']));
    }
    break;
  case 'list':
    $list = scandir('./data');
    $result = [];
    foreach ($list as $value) {
      if ($value !== '.' && $value !== '..') {
        $result[] = ['file' => $value];
      }
    }

    die(json_encode($result));
    break;
  case 'file':
    $name = $_GET['name'];
    $data = file_get_contents('./data/'.$name);
    if (!!$data) {
      die(json_encode([
        'status' => true,
        'message' => 'success file read',
        'data' => json_decode($data)
      ]));
    } else {
      die(json_encode(['status' => false, 'message' => 'error file read']));
    }
    break;
  case 'delete':
    $name = $_GET['name'];
    $dataJS = unlink('./data/'.$name);
    $dataCSV = unlink('./csv/'.$name.'.csv');
    if ($dataJS && $dataCSV) {
      die(json_encode(['status' => true, 'message' => 'success file delete']));
    } else {
      die(json_encode(['status' => false, 'message' => 'error file delete']));
    }
    break;
  default: break;
}

?>
