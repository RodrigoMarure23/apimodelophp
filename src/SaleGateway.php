<?php

class SaleGateway
{
    private PDO $conn; //objeto coneccion a base de datos
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array //funcion para obtener todas las ventas 
    {
        $sql = "SELECT *
                FROM ventas";
                
        $stmt = $this->conn->query($sql);
        
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $row["total"] = (float) $row["total"];
            
            $data[] = $row;
        }
        return $data;
    }
    
    public function create(array $data): string// funcion para crear una venta 
    {
        $sql = "INSERT INTO ventas (fecha, total, id_cliente) 
                VALUES (:fecha, :total, :id_cliente)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":fecha", $data["fecha"], PDO::PARAM_STR);
        $stmt->bindValue(":total", $data["total"] , PDO::PARAM_INT);
        $stmt->bindValue(":id_cliente", $data["id_cliente"], PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $this->conn->lastInsertId(); //regresa el id de la venta creada
    }
    
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM ventas
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data !== false) {
            $data["total"] = (float) $data["total"];
        }
        
        return $data;
    }
    
    public function createSaleDetails(string $id,array $data): int //Agrega los productos a las ventas 
    //  INSERT INTO detalles_venta (id_producto,precio_unitario,cantidad,id_venta) VALUES (:id_producto,:precio_unitario,:cantidad,:id_venta)  TTTTTTT    INSERT INTO productos (id,nombre,precio) VALUES (:id,:nombre,:precio)
    {
        $sql = "INSERT INTO detalles_venta (id_producto,precio_unitario,cantidad,id_venta) VALUES (:id_producto,:precio_unitario,:cantidad,:id_venta)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id_producto", $data["id"] , PDO::PARAM_STR);
        $stmt->bindValue(":precio_unitario", $data["precio_unitario"], PDO::PARAM_INT);
        $stmt->bindValue(":cantidad", $data["cantidad"], PDO::PARAM_INT);
        $stmt->bindValue(":id_venta", $id, PDO::PARAM_INT);
        // $stmt->bindValue(":nombre", $data["nombre"], PDO::PARAM_STR);
        // $stmt->bindValue(":precio", $data["precio"], PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    
    
    public function delete(string $id): int //elimina la venta ingresada
    {
        $sql = "DELETE FROM venta
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}