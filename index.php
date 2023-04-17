<?php
declare(strict_types=1); //definir que las variables sean estrictas

spl_autoload_register(function ($class){//AUTOIMPORTA las clases creadas en el directorio
  require __DIR__ . "/src/$class.php";
});
set_error_handler("ErrorHandler::handleError"); //manejador de errores
set_exception_handler("ErrorHandler::handleException"); //manejardor de errores personalizado


header("Content-type: application/json; charset=UTF-8");// definimos que usaremos json

$parts=explode('/',$_SERVER["REQUEST_URI"]);//descompone la url en partes por "/"
if($parts[2]!="ventas"){
  http_response_code(404);  //direccion no permitida
  echo("No permitido");
  exit;
}
if($parts[2]!="ventas"){
  $database = new Database("localhost","db_ventas","root",""); //Conexion a la base de datos
$gateway = new SaleGateway($database);
$controller = new SaleController($gateway);
$controller -> processRequest($_SERVER["REQUEST_METHOD"],$id);

}

$id=$parts[3] ?? null; //Variable para guardar el id de la venta 

// $database = new Database("localhost","db_ventas","root",""); //Conexion a la base de datos
// $gateway = new SaleGateway($database);
// $controller = new SaleController($gateway);
// $controller -> processRequest($_SERVER["REQUEST_METHOD"],$id);
