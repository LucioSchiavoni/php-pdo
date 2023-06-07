<?php 
class userModel {

    //Conexion a la base de datos
    public $conn;
    public function __construct(){
        $host = 'localhost';
        $dbname = 'server-test-1';
        $username = 'root';
        $password = '';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error al conectar a la base de datos: " . $e->getMessage();
        }
    }

public function saveRestaurant($email, $pwd, $nombre_restaurante, $premios, $tipo_restaurante, $nacionalidad, $tipo_comida) {
    try {
        $stmt = $this->conn->prepare("INSERT INTO restaurantes (email, pwd, nombre_restaurante, premios, tipo_restaurante, nacionalidad, tipo_comida) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $pwd);
        $stmt->bindParam(3, $nombre_restaurante);
        $stmt->bindParam(4, $premios);
        $stmt->bindParam(5, $tipo_restaurante, PDO::PARAM_STR); // Especificar el tipo de dato como string ya que dentro tenemos un ENUM
        $stmt->bindParam(6, $nacionalidad);
        $stmt->bindParam(7, $tipo_comida);
    
        $stmt->execute();
        
        $res = ['success', 'Restaurante guardado'];
    } catch (PDOException $e) {
        $res = ['Error', 'Error al guardar el restaurante: ' . $e->getMessage()];
    }
    
    return $res;
}



}

?>