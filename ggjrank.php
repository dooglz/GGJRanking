<?php
error_reporting(E_ALL);
$fileName ="ggjsites.json";
if(!is_null($_POST['newdata']) &&  strlen ($_POST['newdata']) > 0 ){
  if( !file_exists($fileName) ){
    header('X-Error-Message: no File Error', true, 500);
    die('no File Error');
  }
  if (($fp = fopen($fileName, "w+"))!==false ) {
    fwrite($fp, urldecode ( $_POST['newdata']));
    fclose($fp);
    echo $_POST['newdata'];
  }
  else
  {
    header('X-Error-Message: File Write Error', true, 500);
    die('File Write Error');
  }
  exit();
}

#if(!is_null($_POST['membercount']) &&  strlen ($_POST['membercount']) > 0 ){
##	echo $_POST['membercount'];
##	$myfile = fopen("members.json", "a") or die("Unable to open file!");
##	fwrite($myfile, urldecode ( $_POST['membercount']));
##	exit();
#}

$data = "";
if( !file_exists($fileName) ){
  header('X-Error-Message: no File Error', true, 500);
  die('no File Error');
}
if (($fp = fopen($fileName, "r")) !== false ) {
  $data = fread($fp, filesize($fileName));
  $data = preg_replace('~[\r\n]+~', '', $data);
  fclose($fp);
}
else
{
  header('X-Error-Message: File Write Error', true, 500);
  die('File Write Error');
}

if(!is_null($_GET['data'])){
  header('Content-Type: application/json');
  echo $data;
  exit(0);
}

//$sitesdata = fopen("ggjsites.json", "r") or die("Unable to open file!");
//$napierdata = fopen("members.json", "r") or die("Unable to open file!");
//echo (fread($myfile,filesize("ggjsites.json")));
?>
<!DOCTYPE html>
<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style type="text/css">
      body
      {
        background: #f1f1fa;
        font-family: 'Roboto', sans-serif;
      }
      a {
        text-decoration: underline;
      }
      .chartcontainer{
        background: white;
      }

    </style>
  </head>
  <body>
    <div class="chartcontainer">
    <canvas id="canvas" width="1024" height="768" style="display: block;"></canvas>
    </div>
    <script type="text/javascript"><?php echo("var rawdata = ".$data.";"); ?> </script>
    <script type="text/javascript">
        
    function clicker(e){
      let aa = chart.getElementAtEvent(e);
      if(aa.length){
        console.log(aa);
        return;
      }
    }
    let chart;
    var data = rawdata;
    var total_skills;
    let cuttoff = 1;
    let merged = data.filter((a) => a.jammers <= cuttoff);
    data = data.filter((a) => a.jammers > cuttoff);
    if(merged.length){
      data.push({
        jammers: cuttoff,
        jamsite: merged.length + " Sites Not Shown \n ",
        skills: {},
        url: ""
      });
     }
   
    
    data = data.sort((a,b)=>a.jammers-b.jammers);
    let sites = data.map((e)=>e.jamsite);
    let numbers = data.map((e)=>e.jammers)

    var horizontalBarChartData = {
      labels: sites,
      datasets: [{
      label: 'Jammers',
      backgroundColor: '#ffff33',
      borderColor: '#38e3f6',
      borderWidth: 3,
      data: numbers
      }]
    };

    $( document ).ready(function() {

      var ctx = $("#canvas")[0].getContext('2d');
      chart = new Chart(ctx, {
        type: 'horizontalBar',
        data: horizontalBarChartData,
        options: {
          elements: {
            rectangle: {
              borderWidth: 2,
            }
          },
          responsive: true,
          legend: {
            display: false
          },
          title: {
            display: true,
            text: 'UK GGJ Sites'
          },
          onClick: clicker,
          scales: {
            yAxes: [{
                ticks: {
                    fontSize: 20,
                    fontFamily: "Roboto,helvetica,arial,sans-serif",
                    fontColor: "#38e3f6",
                }
            }]
          },
          plugins: {
            datalabels: {
              color: 'black',
              display: function(context) {
                return true;
                return context.dataset.data[context.dataIndex] > 15;
              },
              font: {
                weight: 'bold'
              },
              formatter: Math.round
            }
          }
        }
      });
    });

    </script>
  </body>
</html>