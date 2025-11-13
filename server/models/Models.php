<?php
abstract class Model{
    protected static string $table;
    protected static string $primary_key = "id";



    public static function findById(mysqli $connection, int $id){
        $sql = sprintf("SELECT * from %s WHERE %s = ?",
                       static::$table,
                       static::$primary_key);

        $query = $connection->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();               

        $data = $query->get_result()->fetch_assoc();

        return $data ? new static($data) : null;
    }
    public static function findByColumn(mysqli $connection, string $columnName, string $value): ?array
    {
        $allowedColumns = ['id', 'email', 'name']; 
        if (!in_array($columnName, $allowedColumns)) {
            throw new InvalidArgumentException("Invalid column name: $columnName");
        }
    
        $sql = sprintf("SELECT * FROM %s WHERE %s = ?", static::$table, $columnName);
    
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $value);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $allData = [];
    
        while ($row = $result->fetch_assoc()) {
            $allData[] = new static($row);
        }
    
        $stmt->close();
    
        return empty($allData) ? null : $allData;
    }

    public static function deleteById(mysqli $connection, int $id) {
         $sql = sprintf("delete from %s where %s=?",
                        static::$table,
                        static::$primary_key);
         $query = $connection->prepare($sql);
         $query->bind_param("i", $id);
         return $query->execute();
    }
    public function deleteByObject(mysqli $connection){
        $sql =sprintf("delete from %s where %s=?",static::$table,static::$primary_key);
        $query=$connection->prepare($sql);
        $query->bind_param("i",$this->getID());
        return $query->execute();

    }
    public function insert(mysqli $connection)
    {
        $data = $this->toArray();
    
        $keys = array_keys($data);
        $placeHolders = implode(',', array_fill(0, count($keys), '?'));
        
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", static::$table, implode(',', $keys), $placeHolders);
    
        $query = $connection->prepare($sql);
    
        
        $types = '';
        $values = [];
        foreach ($data as $value) {
            if (is_int($value)) $types .= 'i';
            elseif (is_float($value)) $types .= 'd';
            else $types .= 's';
            $values[] = $value;
        }
    
        $query->bind_param($types, ...$values);
        if( !$query->execute()){
            return false;
        }
        return $connection->insert_id;
         
    }

 public function update(mysqli $connection ){

    $data = $this->toArray();

    $setParts = [];
    $values = [];
    $types = '';

    foreach ($data as $key => $value) {
        if (isset($value) && $key !== static::$primary_key) {
            $setParts[] = "$key = ?";
            $values[] = $value;

            
            if (is_int($value)) $types .= 'i';
            elseif (is_float($value)) $types .= 'd';
            else $types .= 's';
        }
    }

    $sql = sprintf(
        "UPDATE %s SET %s WHERE %s = ?",
        static::$table,
        implode(', ', $setParts),
        static::$primary_key
    );

    $query = $connection->prepare($sql);

    
    $values[] = $this->getID();
    $types .= 'i';

    $query->bind_param($types, ...$values);

    return $query->execute();
  }

    
    


    
}







?>