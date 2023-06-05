<?php

  require_once('database.php');

  // Database connexion.
  $db = dbConnect();
  if (!$db)
  {
    header ('HTTP/1.1 503 Service Unavailable');
    exit;
  }

  // Check the request.
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  //les get ?url
  $request = substr($_SERVER['PATH_INFO'], 1);
  $request = explode('/', $request);
  $requestRessource = array_shift($request);


if($requestMethod =='GET'&& $requestRessource == 'liste'){
  $request = 'SELECT * FROM grande_table_accidents limit 1000';
      $statement = $db->prepare($request);
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  $data = $result;
}

if ($requestMethod == 'POST' && $requestRessource == 'filtre') {
    $mois = $_POST['mois'];
    $descr_grav = $_POST['descr_grav'];
    $age = $_POST['age'];
  
    // Effectuer la requête en fonction des critères de filtrage
    $request = 'SELECT * FROM grande_table_accidents WHERE 1=1';
  
    if (!empty($mois)) {
      $request .= " AND MONTH(`date`) = '$mois'";
    }
  
    if (!empty($descr_grav)) {
      $request .= " AND descr_grav = '$descr_grav'";
    }
  
    if (!empty($age)) {
      if ($age === '0-20') {
        $request .= " AND age >= 0 AND age <= 20";
      } elseif ($age === '20-40') {
        $request .= " AND age > 20 AND age <= 40";
      } elseif ($age === '40-60') {
        $request .= " AND age > 40 AND age <= 60";
      } elseif ($age === '60-80') {
        $request .= " AND age > 60 AND age <= 80";
      } elseif ($age === '80-90') {
        $request .= " AND age > 80 AND age <= 90";
      }
    }

    $request .= " LIMIT 1000";
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $data = $result;
}

  // Send data to the client.
  header('Content-Type: application/json; charset=utf-8');
  header('Cache-control: no-store, no-cache, must-revalidate');
  header('Pragma: no-cache');
  header('HTTP/1.1 200 OK');
  echo json_encode($data);
  exit;
?>
