<?php

class SaleController
{
    public function __construct(private SaleGateway $gateway)
    {
    }
    
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            
            $this->processResourceRequest($method, $id);
            
        } else {
            
            $this->processCollectionRequest($method);
            
        }
    }
    
    private function processResourceRequest(string $method, string $id): void
    {
        $Sale = $this->gateway->get($id);
        
        if ( ! $Sale) {
            http_response_code(404);
            echo json_encode(["message" => "Sale not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode($Sale);
                break;
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data, false);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $rows = $this->gateway->createSaleDetails($id, $data);
                
                echo json_encode([
                    "message" => "venta con id $id sus productos han sido agregados",
                    "rows" => $rows
                ]);
                break;
                
            case "DELETE":
                $rows = $this->gateway->delete($id);
                
                echo json_encode([
                    "message" => "Sale $id deleted",
                    "rows" => $rows
                ]);
                break;
                
            default:
                http_response_code(405);
                header("Allow: GET,DELETE,POST"); //patch?
        }
    }
    
    private function processCollectionRequest(string $method): void
    {
        switch ($method) { //switch para los casos validos de verbos http
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true); //variable que almacena los datos de las peticiones del front
                
                $errors = $this->getValidationErrors($data); // validaciones de los datos obtenidos del front
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $id = $this->gateway->create($data);
                
                http_response_code(201);
                echo json_encode([
                    "message" => "Sale created successfully", //venta creada exitosamente
                    "id" => $id
                ]);
                break;
            
            default:
                http_response_code(405);
                header("Allow only: GET, POST"); //validaciones de verbos http aceptados
        }
    }
    
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        
        if ($is_new && empty($data["nombre"])) {
            $errors[] = "nombre is required";
        }
        
        if (array_key_exists("precio", $data)) {
            if (filter_var($data["precio"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "precio must be an integer";
            }
        }


        
        return $errors;
    }
}