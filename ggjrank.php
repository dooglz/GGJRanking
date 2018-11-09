<?php
error_reporting(E_ALL);
$fileName = "ggjsites.json";
if (!is_null($_POST['newdata']) && strlen($_POST['newdata']) > 0) {
  if (!file_exists($fileName)) {
    header('X-Error-Message: no File Error', true, 500);
    die('no File Error');
  }
  if (($fp = fopen($fileName, "r+")) !== false) {
    $rawfile = fread($fp, filesize($fileName));
    $data    = json_decode($rawfile);
    $toMerge = json_decode(urldecode($_POST['newdata']));
    
    foreach ($toMerge as $newSite) {
      $matchedExistingSite = null;
      foreach ($data as $matchedExistingSite) {
        if ($newSite->jamsite == $matchedExistingSite->jamsite) {
          $item = $matchedExistingSite;
          break;
        }
      }
      if ($item != null) {
        //merge
        $matchedExistingSite->jammers = array_merge($matchedExistingSite->jammers, $newSite->jammers);
      } else {
        //new site
        array_push($data, $newSite);
      }
    }
    
    $mergedData = json_encode($data);
    //Empty the file
    ftruncate($fp, 0);
    fseek($fp, 0);
    //Write to File
    fwrite($fp, $mergedData);
    fclose($fp);
    //Return Data;
    header('Content-Type: application/json');
    echo $mergedData;
    exit(0);
  } else {
    header('X-Error-Message: File Write Error', true, 500);
    die('File Write Error');
  }
  exit();
}

#if(!is_null($_POST['membercount']) &&  strlen ($_POST['membercount']) > 0 ){
##    echo $_POST['membercount'];
##    $myfile = fopen("members.json", "a") or die("Unable to open file!");
##    fwrite($myfile, urldecode ( $_POST['membercount']));
##    exit();
#}

$data = "";
if (!file_exists($fileName)) {
  header('X-Error-Message: no File Error', true, 500);
  die('no File Error');
}
if (($fp = fopen($fileName, "r")) !== false) {
  $data = fread($fp, filesize($fileName));
  $data = preg_replace('~[\r\n]+~', '', $data);
  fclose($fp);
} else {
  header('X-Error-Message: File Write Error', true, 500);
  die('File Write Error');
}

if (!is_null($_GET['data'])) {
  header('Content-Type: application/json');
  echo $data;
  exit(0);
}

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
    <div class="chartcontainer">
    <canvas id="canvas2" width="1024" height="768" style="display: block;"></canvas>
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

    function genData(cnt,scaler = 1){
      let ret = [];
      let last = 1;
      for (let index = 0; index < cnt; index++) {
       last = last + Math.round((scaler*10) * Math.random())
       ret.push(last);
      }
      return ret;
    }

    var data = rawdata;
    let points = data.reduce((acc,cv)=>{return Math.max(acc,cv.jammers.length)},1);
    let tempDates = [ ...Array(points).keys() ];
    let colours = ["#1f77b4", "#ff7f0e", "#2ca02c", "#d62728", "#9467bd", "#8c564b", "#e377c2", "#7f7f7f", "#bcbd22", "#17becf"];
    let chart,chart2;

    var total_skills;
    let cuttoff = 1;

    //Find latestJammersCount
    data.forEach((e)=>{
      if(e.jammers.length == 0){
        e.latestJammersCount =0; return;
      }
      e.latestJammersCount = e.jammers.reduce((acc,cv)=>{if (new Date(acc.date) > new Date(cv.date)){return cv;}return acc; },e.jammers[0]).count
    });

    //Split Sites that have not enough jammers into merged.
    let merged = data.filter((a) => a.latestJammersCount <= cuttoff);
    data = data.filter((a) => a.latestJammersCount > cuttoff);
    if(merged.length){
      data.push({
        latestJammersCount: cuttoff,
        jamsite: merged.length + " Sites Not Shown \n ",
        skills: {},
        url: ""
      });
     }
    //Sort by jammers
    data = data.sort((a,b)=>a.latestJammersCount-b.latestJammersCount);
    //Add Relevant data tags for line chart
    data.forEach((e,i)=>{
      e.label = e.jamsite; 
      //e.data = genData(10,e.latestJammersCount); 
      if(e.jammers == undefined){
        e.data = Array(points).fill(cuttoff);
      }else{
        e.data = e.jammers.map((e)=>e.count);
      }
      e.backgroundColor=colours[i%colours.length];
    });
    //Gather data arrays for bar chart
    let sites = data.map((e)=>e.jamsite);
    let numbers = data.map((e)=>e.latestJammersCount)

    //Setup chartJs
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
      chart = new Chart($("#canvas")[0].getContext('2d'), {
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
      
      
      var lineChartData = {
        labels: tempDates,
        datasets: data
      };
      chart2 = 	new Chart($("#canvas2")[0].getContext('2d'), {
        type: 'line',
        data: lineChartData,
        options: {
          maintainAspectRatio: false,
          spanGaps: false,
          elements: {
            line: {
              tension: 0.000001
            }
          },
          scales: {
            xAxes: [{
              type: 'time',
              distribution: 'series',
              ticks: {
                source: 'labels'
              }
					  }],
            yAxes: [{
              stacked: true
            }]
          },
          plugins: {
            filler: {
              propagate: false
            },
            'samples-filler-analyser': {
              target: 'chart-analyser'
            }
          }
        }
      });
    });

    </script>
  </body>
</html>