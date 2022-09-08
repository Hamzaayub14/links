<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>
<body class="container m-5">
   
        <form  method="post">    

            <div class="form-group">
                <label for="exampleInputEmail1">Enter Your URL</label>
                <input type="text" class="form-control mb-2" name='url' id="" placeholder="Enter Your URL">
                <input class="btn btn-primary" type="submit">
              </div>
    </form>
    
   

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

</body>
</html>


<?php
error_reporting(0);
$url=$_POST['url'];
// $url = "https://www.tesla.com";
// echo $url;
$len= strlen($url)-1;
include("simple_html_dom.php");
if($url[$len]=="/"){
$concatenated = rtrim($url, '/\\');
}
else{
    $concatenated=$url;
}
$html = file_get_html($concatenated);
// $p=implode(".");
// echo $p."\n";

$pieces = explode("/",$concatenated);
$ur=    $pieces[2];



$pieces3 = explode(".",$ur);


 

$www ='www';





if (strpos($ur, $www) !== false){
        $sname = $pieces3[1];
        $com = $pieces3[2];
        $fname=$ur;
        
}

else{
        $sname = $pieces3[0];
        $com = $pieces3[1];
        $fullname=$sname.".".$com;
}
// echo $fullname;

// // Find all links 
$allURLs = array();
$internalcount=0;
$externalurls=0;
$totalcount=0;

$i=0;
$a =[];
$top2=[];
$needle   = '/';
$needle2   = '/';
$arr=[];
$arr2=[];
$html2=0;

$con = 0;

foreach($html->find('a') as $element) 
{
       
// echo $element->href ."\n";
if($element->href[0]==""){
 
        $element->href = $concatenated.$element->href;
 
}
if($element->href[0]=='/'){

        $element->href = $concatenated.$element->href;
     }
     elseif($element->href[0]=='#'){
        $element->href = $concatenated."/".$element->href;
     }

//    echo $con."\n";     


        $totalcount++;
//         // $haystack = 'How are you?';


// $element->href =str_replace('www.','',$element->href);
// echo $element->href."\n";



if (strpos($element->href, $needle) !== false) {
        $p=explode("/",$element->href);
        // echo $p[2]."\n";

        $needle ='.';

        if(strpos($p[2], $needle) !== false){
                $q=explode(".",$p[2]);
                // print_r($q);
        }
        // echo $q[0]."\n";

        if (strpos($ur, $www) !== false){
                if($q[1] != $sname || $q[2] != $com){
                        $externalurls++;
                        $arr[$i]=$element->href;
                        $i++;
                        // echo $sname;
                }
                else{
                        $arr[$i]=$element->href;
                        $i++;
                }
                
        }
        
        else{
                if($q[0] != $sname || $q[1] != $com){
                        $externalurls++;
                        $arr[$i]=$element->href;
                        $i++;
                        // echo $sname;
                }
                else{
                        $arr[$i]=$element->href;
                        $i++;
                }
        }
    
        
}

}
// print_r($arr);

// $arr = (array_unique($arr));

// echo "T".$totalcount;
// echo"Ex".$externalurls;
foreach($arr as $key=>&$e){
        
    // echo $e."\n";

    $context = stream_context_create(
            array(
                'http' => array(
                    'follow_location' => false
                )
            )
        );
        $contents = file_get_contents($e, false, $context);
    
    
    
    // $contents = file_get_contents($e);
   
    $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
    '@<head>.*?</head>@siU',            // Lose the head section
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
);

 $contents = preg_replace($search, '', $contents); 
 
 $result = array_count_values(
  str_word_count(
      strip_tags($contents), 1
      )
  );
 
 $a = array_sum($result);
       
 $top2[$key] =$a.",".$e;
}


$ba = [];
$int=[];
$ext=[];
$tot=[];
$u=0;
$slice = [];
$ran2 = [];
$ran=[];
// function sortby($top2,$sname,$com,$concatenated,$ur,$www){
    
    $totalexternal = 0;
    $totalinternal = 0;
    $FinalTotal = 0;
    $data_temp =array();
    foreach($top2 as $item){
            // echo $item."\n";
    $data_temp[] = explode(",",$item);
    }

    // $data_temp = (array_unique($data_temp));
    // print_r($data_temp);
$internal=0;
$total =0;
$external=0;
$con=0;

$needle ='.';


$sortarray = array();
foreach ($data_temp as $key => $row)
{
    $sortarray[$key] = $row[0];
}

array_multisort($sortarray, SORT_DESC, $data_temp);


for($n=0;$n<=count($data_temp);$n++){
$ba[$n] = $data_temp[$n][1];
}

$ba = (array_unique($ba));
// print_r($ba);
// sort_array_of_array($data_temp, 0);
$slice = array_slice($ba,0,15);




// print_r ($slice);

$count1 = 0;
$needle = "/";
$u = 0;

foreach($slice as $key=>$links){

    $ran2 = $links;
 
    
    $ran = file_get_html($ran2);

    foreach($ran->find('a') as $element2) 
{
    if($element2->href[0]==""){
 
        $element2->href = $concatenated.$element2->href;
 
}
elseif($element2->href[0]=='/'){

        $element2->href = $concatenated.$element2->href;
     }
     elseif($element2->href[0]=='#'){
        $element2->href = $ran2.$element2->href;
     }

    //  echo $element2->href."\n";

    $count1++;

    if (strpos($element2->href, $needle) !== false) {
        $p=explode("/",$element2->href);
        // echo $p[2]."\n";

        $needle ='.';

        if(strpos($p[2], $needle) !== false){
                $q=explode(".",$p[2]);
                // print_r($q);
        }

       

        if (strpos($ur, $www) !== false){
                if($q[1] != $sname || $q[2] != $com){
                        $external++;
                        $arr2[$i]=$element2->href;
                        $i++;
                        // echo $sname;
                }
                else{
                        $arr2[$i]=$element2->href;
                        $i++;
                }
                
        }
        
        else{
                if($q[0] != $sname || $q[1] != $com){
                        $external++;
                        $arr2[$i]=$element2->href;
                        $i++;
                        // echo $sname;
                }
                else{
                        $arr2[$i]=$element2->href;
                        $i++;
                }
        }
    
        
}

}
    

 $totalinternal = $count1-$external;

$int[$u]=$totalinternal;
$ext[$u]=$external;
$tot[$u]=$count1;

$external=0;
$count1=0;
$totalinternal=0;

 
     $u++;  

}



?>


<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>
<body class="container m-5">
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Links</th>
                <th>Total Links</th>
                <th>Internal Links</th>
                <th>External Links</th>
             
            </tr>
        </thead>
        <tbody>
        <?php for($s=0;$s<=14;$s++){
?>
</tr>
<td><?php echo $slice[$s];?></td>
<td><?php echo $tot[$s];?></td>
<td><?php echo $int[$s];?></td>

<td><?php echo $ext[$s];?></td>
</tr>
<?php }
?>
   
</tbody>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
    $('#example').DataTable();
});
</script>
</body>
</html>
