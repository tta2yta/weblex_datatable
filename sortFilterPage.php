<?php  
include_once './model/model_cl.php';
 
 try {
  $model_cl = new model_cl();

 $output = '';  
 if(isset($_POST['order']))
 $order =htmlspecialchars(strip_tags( $_POST['order']));  

  if(isset($_POST['page']))
  $currPage=htmlspecialchars(strip_tags($_POST['page']));

  if(isset($_POST['filterColumn']))
  $filterColumn=htmlspecialchars(strip_tags($_POST['filterColumn']));

      if(isset($_POST['orderColumn']))
  $orderColumn=htmlspecialchars(strip_tags($_POST['orderColumn']));


     if(isset($_POST['query']))
  $query=htmlspecialchars(strip_tags($_POST['query']));

  if(isset($_POST['condition']))
  $condition=$_POST['condition'];

if(!isset($order) || !isset($currPage) || !isset($filterColumn) || !isset($orderColumn) || !isset($query) || !isset($condition))
  //exit("Please Try Again Later");
 exit("Пожалуйста, Повторите Попытку Позже");

 if($order == 'desc')  
 {  
      $order = 'asc';  
 }  
 else  
 {  
      $order = 'desc';  
 }  

$limit = '1';
$page = 1;
if($currPage > 1)
{
  $start = (($currPage - 1) * $limit);
  $page = $currPage;
}
else
{
  $start = 0;
  $page = 1;
}
  $result=$model_cl->search($filterColumn,$orderColumn, $query,$condition,$order,$start,$limit);
 // $result=$model_cl->search($_POST['filterColumn'],$_POST['orderColumn'], $_POST['query'],$_POST['condition'],$order,$start,$limit);
  $total_data = $model_cl->numRecords;
  $output .= '
 <table class="table table-bordered">  
      <tr>  
           <th><a class="column_sort" id="date" class="fa fa-sort" data-order="'.$order.'" href="#">Дата</a></th>  
           <th><a class="column_sort" id="Name" data-order="'.$order.'" href="#">Название</a></th>  
           <th><a class="column_sort" id="Quantity" data-order="'.$order.'" href="#">Количество</a></th>  
           <th><a class="column_sort" id="Distance" data-order="'.$order.'" href="#">Расстояние</a></th>   
      </tr>  ';   
 if($result != false) {
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
 
      $output .= '  
      <tr>  
           <td>' . $row["Date"] . '</td>  
           <td>' . $row["Name"] . '</td>  
           <td>' . $row["Quantity"] . '</td>  
           <td>' . $row["Distance"] . '</td>   
      </tr>  
      ';  
 }
   
 } else {
   echo "Пожалуйста, попробуйте еще раз";
    //echo "Please Try again";
 }
    
 $output .= '</table>';  

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';
if ($total_links == 0) {
  echo $output;
  exit("Никаких Записей Не Найдено Пожалуйста Попробуйте Использовать Другой Критерий ");
  // exit("No Recors Found Please Try With Another Criteria ");
}
// echo"total_links=". $total_links;
// echo"<br>";
// echo"page=". $page;
// echo"<br>";
if($total_links > 4 )
{
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
    
  }
  else
  {
    $end_limit = $total_links - 5;
    //echo"end_limit=". $end_limit;
    //echo"<br>";
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
   
    }
  }
}
else
{
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}
for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#" data-page_number="'.$page.'">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
    </li>
    ';
    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item" ><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Предыдущий</a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Предыдущий</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id > $total_links)
    {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Следующий</a>
      </li>
        ';
    
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Следующий</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {

      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';

 echo $output;  

} catch (Exception $e) {
 // echo "Pleas Try Again";
   echo "Пожалуйста, попробуйте еще раз";

}
 
 
 ?>  