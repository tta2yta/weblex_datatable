<?php 
include_once "./Database.php";
  class model_cl {

    public $numRecords='';


    public function __construct() {
       $database = new Database();
  $db = $database->connect();
     $this->conn = $db;
     $this->numRecords=0;
    }

    public function sort($column_name, $order) {
      // Create query
       $query = "SELECT * FROM tbl_task ORDER BY ".$_POST["column_name"]." ".$_POST["order"].""; 
      try {
         // Prepare statement
      $stmt = $this->conn->prepare($query);
      // Execute query
      $stmt->execute();

      return $stmt;
        
      } catch (Exception $e) {
        return false;
      }
     
    }

      public function read() {
      // Create query
       $query = "SELECT * FROM tbl_task ORDER BY Name DESC";
       try {
        $stmt = $this->conn->prepare($query);
      $stmt->execute();

      return $stmt;
         
       } catch (Exception $e) {
         return false;
       }
      
    }

    public function search($filterColumn,$orderColumn, $serh_val, $condition,$ord,$start,$limit){
      $numFlag=false;
      $serh_val=$this->checkData($serh_val);
     try {
       $query = "SELECT * FROM tbl_task";
       if($serh_val !=''){
if( $condition != 'LIKE')
{
 
   $query .= " WHERE $filterColumn $condition :serh_val "; 
   if($filterColumn=='Quantity' || $filterColumn=='Distance')
    $numFlag==true;
}

elseif ( $condition == 'LIKE') {
 $query .= " WHERE  $filterColumn  $condition  :serh_val";
 $serh_val="%".$serh_val."%";
}
}
else {
 $query = "SELECT * FROM tbl_task";
}
$filterQuery= $query ." order by ".$orderColumn.' '. $ord;
$query .=" order by ".$orderColumn.' '. $ord . " LIMIT ". $start.', '. $limit;
 $this->numRecord($filterQuery, $serh_val, $numFlag);

  $stmt =  $this->conn->prepare($query);
 if($numFlag==true && $serh_val != '')
$stmt->bindValue(':serh_val', $serh_val,PDO::PARAM_INT);
else
 $stmt->bindValue(':serh_val', $serh_val,PDO::PARAM_STR);
$stmt->execute();

return $stmt;
     } catch (Exception $e) {
       return false;
     }

    }

public function numRecord($filterQuery, $serh_val, $numFlag)
{
  try {
     $stmt =  $this->conn->prepare($filterQuery);
     if($numFlag==true && $serh_val != '')
     $stmt->bindValue(':serh_val', $serh_val,PDO::PARAM_INT);
     else
     $stmt->bindValue(':serh_val', $serh_val,PDO::PARAM_STR);
     $stmt->execute();
     $this->numRecords=$stmt->rowCount();
    
  } catch (Exception $e) {
    return 0;
  }
 
}

function checkData($serh_val) {
            $data = trim($serh_val);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

 }
   ?>