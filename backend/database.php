<?php 

  /**
   * Configuração do banco de dados. Esta classe é utilizada para se conectar ao MySQL.
   */
  class Database {

    private $host = 'localhost';

    private $db_name = 'trabalho_final_eng_soft';

    private $username = 'root';

    private $password = '';

    private $conn;

    // private $db_name = 'giotac39_trabalho_final_eng_soft';
    // private $username = 'giotac39_user';
    // private $password = '12345';
    

    /**
     * Função que se conecta ao banco de dados.
     */
    public function connect() {
      $this->conn = null;

      try { 
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }