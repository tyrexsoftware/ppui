<?php
/* @var $this yii\web\View */
use app\addons\helpers\HomePage;

$this->title = 'My Yii Application';

$this->registerJs('', \yii\web\View::POS_READY);
$homepageinfo = new HomePage();
?>
<div class="site-index">
<div class="graphic_overview">
  <div class="graphic_block">
    <h2 class="graph_title"><span>Test by date</span></h2>
    <div id="curve_chart"></div>    
    <!--<div class="bottom_line">
      <div class="line_1"><span class="blue">&nbsp;</span><div><span>Lorem ipsum</span>Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae</div></div>
      <div class="line_2"><span class="orange">&nbsp;</span><div><span>Lorem ipsum</span>Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae</div></div>
    </div>-->
  </div>
  <div class="extra_info">
    <h2 class="graph_title"><span>Subscription info</span></h2>
    <ul class="extra_block">
      <li><span class="label_title">Organization Animals:</span><?=$homepageinfo->totalanimals?></li>
      <li><span class="label_title">My Animals:</span><?=$homepageinfo->totaluseranimals?></li>
      <li><span class="label_title">Devices:</span><?=$homepageinfo->totaldevices?></li>
      <li><span class="label_title">Users:</span><?=$homepageinfo->totalusers?></li>
      <li><span class="label_title">Expires:</span><?=$homepageinfo->exiperydate?></li>
    </ul>
    <h2 class="graph_title border_title"><span>Last Sync</span></h2>
    <ul class="extra_block">
      <li><span class="label_title">Date:</span><?=$homepageinfo->lastsyncday?></li>
      <li><span class="label_title">Animals:</span><?=$homepageinfo->numberofsyncedanimals?></li>
    </ul>
    <h2 class="graph_title border_title"><span>Latest Version</span></h2>
    <ul class="extra_block">
      <li><span class="label_title">IOS:</span>v. <?=$homepageinfo->latestandroidversion?></li>
      <li><span class="label_title">Android:</span>v. <?=$homepageinfo->latestiosversion?></li>
      
    </ul>
  </div>
</div>
<h2 class="graph_title border_title"><span>Colony</span></h2>
<div class="columns_three">
  <div class="column_1">
  <div class="column_wrapper">
    <h3><span>Behaviour</span></h3>
    <div id="blue_circle"></div>
    </div>
  </div>
  <div class="column_2">
  <div class="column_wrapper">
    <h3><span>Novel Overview</span></h3>
    <div id="orange_circle"></div>   
    </div>
  </div>
  <div class="column_3">
  <div class="column_wrapper">
    <h3><span>Quis autem</span></h3>
    <div id="green_circle"></div>   
    </div>
  </div>
</div>
</div>

      <?php 
      echo '<pre>';
     // print_r();
     foreach ($homepageinfo->getAppUsage() as $a){
         echo '<pre>';
         print_r($a);
         echo '</pre>';
     }
     
      echo '</pre>';
      ?>

<script type="text/javascript">
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Date', 'Lorem ipsum ', 'Quis autem vel'],
          [new Date(2015, 1, 1),  1000,      400],
          [new Date(2015, 2, 1),  1170,      460],
          [new Date(2015, 3, 1),  660,       1120],
          [new Date(2015, 4, 1),  1030,      540],
          [new Date(2015, 5, 1),  1030,      540],
          [new Date(2015, 6, 1),  1030,      540]
        ]);
        var options = {
/*          curveType: 'function',*/
          legend: { position: 'bottom' },
          colors:['#f39c12', '#51b0ce'],
          width:'100%',
          height:418,
          chartArea:{left:40, top:20, width: '85%'},
					hAxis:{maxValue:new Date(2015, 6, 1)},
					vAxis:{minValue:0, maxValue:1170},
					animation:{
						duration: 1000,
						easing: 'inAndOut',
						startup: true
					}
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
				var data1 = google.visualization.arrayToDataTable([
          ['Date', 'Lorem ipsum ', 'Quis autem vel'],
          [new Date(2015, 1, 1),  0,      0],
          [new Date(2015, 2, 1),  0,      0],
          [new Date(2015, 3, 1),  0,       0],
          [new Date(2015, 4, 1),  0,      0],
          [new Date(2015, 5, 1),  0,      0],
          [new Date(2015, 6, 1),  0,   0]
        ]);
				chart.draw(data1, options);
        chart.draw(data, options);
      }
      
</script>
<script type="text/javascript">
google.setOnLoadCallback(drawChart);
      function drawChart() {
        var options = {
          colors:['#1783a7', '#51b0ce'],
          height: 250,
          chartArea:{left:0, top:7, width:'100%', height: 200},
          fontSize:20,
          legend: {textStyle:  {fontSize: 14,bold: false}, position: 'bottom'},          
          tooltip: {textStyle:  {fontSize: 14,bold: true}},
          animation:{
            duration: 2000,
            easing: 'inAndOut',
            startup: true
          }
        };

          var data = google.visualization.arrayToDataTable([
            ['Examples', 'Percent'],
            ['Test1',     100],
            ['Test2',     0]
          ]);    
        var chart = new google.visualization.PieChart(document.getElementById('blue_circle'));
        
				chart.draw(data, options); 

        for(i=0; i<=35; i++){
          (function (index){
            data1 = [];
            var data1 = google.visualization.arrayToDataTable([
                ['Examples', 'Percent'],
                ['Test1',     100-index],
                ['Test2',     index]
              ]);
              setTimeout(function(){              
              chart.draw(data1, options);
          },0+(i*40));
          })(i)
        }
      }

      
</script>
<script type="text/javascript">
google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Examples', 'Percent'],
          ['Test1',     45],
          ['Test2',     45],
          ['Test3',  10]
        ]);
        var options = {
          colors:['#d74613', '#f37112', '#f39c12'],
          height: 250,
          chartArea:{left:0, top:7, width:'100%', height: 200},
          fontSize:20,
          legend: {textStyle:  {fontSize: 14,bold: false}, position: 'bottom'},
          tooltip: {textStyle:  {fontSize: 14,bold: true}}
        };
        var chart = new google.visualization.PieChart(document.getElementById('orange_circle'));
        chart.draw(data, options);
        for(i=0; i< 46; i++){
          (function (index){
            data1 = [];
            var data1 = google.visualization.arrayToDataTable([
                ['Examples', 'Percent'],
                ['Test1',     index],
                ['Test2',     index],
                ['Test3',     100-2*index]
              ]);
              setTimeout(function(){              
              chart.draw(data1, options);
          },0+(i*40));
          })(i)
        }
      }
</script>
<script type="text/javascript">
google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Examples', 'Percent'],
          ['Test1',     45],
          ['Test2',     45],
          ['Test3',  10]
        ]);
        var options = {
          colors:['#388e3c', '#4db052', '#81c784'],
          height: 250,
          chartArea:{left:0, top:7, width:'100%', height: 200},
          fontSize:20,
          legend: {textStyle:  {fontSize: 14,bold: false}, position: 'bottom'},
          tooltip: {textStyle:  {fontSize: 14,bold: true}}
        };
        var chart = new google.visualization.PieChart(document.getElementById('green_circle'));
        chart.draw(data, options);
        for(i=0; i< 46; i++){
          (function (index){
            data1 = [];
            var data1 = google.visualization.arrayToDataTable([
                ['Examples', 'Percent'],
                ['Test1',     index],
                ['Test2',     index],
                ['Test3',     100-2*index]
              ]);
              setTimeout(function(){              
              chart.draw(data1, options);
          },0+(i*40));
          })(i)
        }
      }
</script>