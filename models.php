<?php
 class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "contact_app";
    public $conn;

    // Koneksi ke database
    public function getConnection(){
        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Koneksi database gagal: " . $exception->getMessage();
        }

        return $this->conn;
    }
}   
?>

<?php
class Contact {
    // Properti untuk koneksi database
    private $conn;
    private $table_name = "contacts";

    // Properti dari objek kontak
    public $id;
    public $nomor_hp;
    public $owner;

    // Constructor untuk kelas Contact
    public function __construct($db){
        $this->conn = $db;
    }

    // Fungsi Create (Tambah data kontak)
    function create(){
        $query = "INSERT INTO " . $this->table_name . " SET nomor_hp=:nomor_hp, owner=:owner";
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(":nomor_hp", $this->nomor_hp);
        $stmt->bindParam(":owner", $this->owner);

        // Eksekusi query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // Fungsi Read (Ambil semua data kontak)
    function read(){
        $query = "SELECT id, nomor_hp, owner FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Fungsi Update (Update data kontak)
    function update(){
        $query = "UPDATE " . $this->table_name . " SET nomor_hp=:nomor_hp, owner=:owner WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":nomor_hp", $this->nomor_hp);
        $stmt->bindParam(":owner", $this->owner);

        // Eksekusi query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // Fungsi Delete (Hapus data kontak)
    function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(1, $this->id);

        // Eksekusi query
        if($stmt->execute()){
            return true;
        }

        return false;
    }
}
?>
<?php
// Menggunakan kelas Database untuk membuat koneksi
include_once 'models.php';

// Membuat objek database
$database = new Database();
$db = $database->getConnection();

// Membuat objek contact
$contact = new Contact($db);

// // Contoh penggunaan fungsi Create
// $contact->nomor_hp = "085762536271";
// $contact->owner = "Jessyca Adelia";
// if($contact->create()){
//     echo "Kontak berhasil ditambahkan.";
// } else{
//     echo "Gagal menambahkan kontak.";
// }

// Contoh penggunaan fungsi Read
$stmt = $contact->read();
$num = $stmt->rowCount();

if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        echo "ID: {$id}, Nomor HP: {$nomor_hp}, Owner: {$owner}<br>";
    }
} else {
    echo "Tidak ada kontak yang ditemukan.";
}

// Contoh penggunaan fungsi Update
$contact->id = 1;
$contact->nomor_hp = "085762536271";
$contact->owner = "Jessyca Adelia";
if($contact->update()){
    echo "Kontak berhasil diperbarui.";
} else{
    echo "Gagal memperbarui kontak.";
}

// // Contoh penggunaan fungsi Delete
// $contact->id = 1;
// if($contact->delete()){
//     echo "Kontak berhasil dihapus.";
// } else{
//     echo "Gagal menghapus kontak.";
// }
// ?>

