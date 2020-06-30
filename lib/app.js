 $(document).ready(function(){  
  var query='';
   var order='';
   var col_name='';
   var condition='=';
   var filterColumn='Name';
   var orderColumn='Name';
   var column_name='';
   var arrow='';
   var page= 1;

   load_data(orderColumn, filterColumn, query, order, condition, page);

    function load_data(orderColumn,filterColumn, query,order,condition,page)
    {
        jQuery.ajax({  
                url:"sortFilterPage.php",  
                method:"POST",  

               data:{orderColumn:orderColumn,filterColumn:filterColumn,query:query,order:order,condition:condition,page:page},
                success:function(data)  
                {  
                     $('#employee_table').html(data);  
                       $('#'+orderColumn+'').append(arrow);  
                   
                }  
           }) 
    }


      $(document).on('click', '.column_sort', function(){  
            orderColumn = $(this).attr("id");  
           order = $(this).data("order");    
           if(order == 'desc')  
           {  
                arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';  
           }  
           else  
           {  
                arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';  
           }  
        
           query=document.getElementById("search_box0").value;
           load_data(orderColumn, filterColumn, query, order, condition, page); 
      }); 


    $('#search_box0').keyup(function(){
       query = $('#search_box0').val();
       page=1;
      load_data(orderColumn, filterColumn, query, order, condition, page);
    });

    $("select.col_names").change(function(){
         filterColumn = $(this).children("option:selected").val();
       
    });

     $("select.col_conditions").change(function(){
         condition = $(this).children("option:selected").val();
         
       
    });


       $(document).on('click', '.page-link', function(){
       page = $(this).data('page_number');
       query=document.getElementById("search_box0").value;
       load_data(orderColumn, filterColumn, query, order, condition, page);
    });


 });   