<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Allow: *");

header("content-type: application/json; charset=utf-8");


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


    //GET
public function getUsers($id = null) {
    $where = ($id == null) ? "" : " WHERE id=:id ";
    $usuarios = [];

    $sql = "SELECT * FROM turistas " . $where;
    $stmt = $this->conn->prepare($sql);

    if ($id != null) {
        $stmt->bindParam(':id', $id);
    }

    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $usuarios[] = $row;
    }

    return $usuarios;
}


//POST
public function saveTurist($nombre, $email, $pwd, $hotel, $fecha_inicio,$fecha_fin, $barrio)
{
    $validate = $this->existUser($email);

    if (count($validate) > 0) {
        $res = ["Error", "Este usuario ya existe"];

    }else if($fecha_inicio > $fecha_fin){
        $res= ['error', 'Error en la fecha'];
    }else{
        $password = password_hash($pwd, PASSWORD_DEFAULT); //Hash de la password
        try {
            $stmt = $this->conn->prepare("INSERT INTO turistas (nombre, email, pwd, hotel, fecha_inicio,fecha_fin, barrio) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $password);
            $stmt->bindParam(4, $hotel);
            $stmt->bindParam(5, $fecha_inicio);
            $stmt->bindParam(6, $fecha_fin);
            $stmt->bindParam(7, $barrio);
        
            $stmt->execute();
            
            $res = ['success', 'Usuario guardado'];
        } catch (PDOException $e) {
            $res = ['Error', 'Error al guardar el usuario: ' . $e->getMessage()];
        }
    }
    return $res;
}

//UPDATE
public function updateUsers($id, $nombre, $email, $pwd, $hotel, $fecha, $barrio){
    try {
        $sql ="UPDATE turistas SET nombre=:nombre, email=:email, pwd=:pwd, hotel=:hotel, fecha=:fecha, barrio=:barrio WHERE id=:id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pwd', $pwd);
        $stmt->bindParam(':hotel', $hotel);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':barrio', $barrio);
        $stmt->bindParam(':id', $id);
        

    $stmt->execute();

    $res = ['success', 'Usuario actualizado'];

    return $res;

    } catch (PDOException $e) {
        $res = ['Error', 'Error al actualizar usuario: ' . $e->getMessage()];
        return $res;
    }
}

//DELETE
public function deleteUsers($id) {
    $validate = $this->getUsers($id);
    $res = ["error", "No existe el producto"];

    if (count($validate) > 0) {
        $sql = "DELETE FROM turistas WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $res = ["success", "Usuario eliminado"];
    }

    return $res;
}


//Exist User 
public function existUser($email) {
    $users = [];
    $sql = "SELECT * FROM turistas WHERE email=:email";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }

    return $users;
}


//Auth User
public function loginUser($email, $pwd) {
    // Verificar si el usuario existe en la base de datos
    $users = $this->existUser($email);

    if(count($users) > 0){
        $user = $users[0];
        if (password_verify($pwd, $user['pwd'])) {
                // Usuario autenticado correctamente
                return ['success', 'Usuario autenticado'];
            }
    }
    
    return ['error', 'Credenciales inválidas'];
    // Devolver una respuesta JSON indicando el error del inicio de sesión
    


}

}

?>