<?php
/* @var $this yii\web\View */

use app\addons\helpers\HomePage;

$this->title = 'Primate Profiler Dashboard';

$this->registerJs('', \yii\web\View::POS_READY);
$homepageinfo = new HomePage();
?>
<div class="site-index">
    <div class="graphic_overview">
        <div class="graphic_block">
            <h2 class="graph_title"><span>Module usage by month</span></h2>
            <div id="curve_chart"></div>    
            <!--<div class="bottom_line">
              <div class="line_1"><span class="blue">&nbsp;</span><div><span>Lorem ipsum</span>Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae</div></div>
              <div class="line_2"><span class="orange">&nbsp;</span><div><span>Lorem ipsum</span>Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae</div></div>
            </div>-->
        </div>
        <div class="extra_info">
            <h2 class="graph_title"><span>Subscription info</span></h2>
            <ul class="extra_block">
                <li><span class="label_title">Organization Animals:</span><?= $homepageinfo->totalanimals ?></li>
                <li><span class="label_title">My Animals:</span><?= $homepageinfo->totaluseranimals ?></li>
                <li><span class="label_title">Devices:</span><?= $homepageinfo->totaldevices ?></li>
                <li><span class="label_title">Users:</span><?= $homepageinfo->totalusers ?></li>
                <li><span class="label_title">Expires:</span><?= $homepageinfo->exiperydate ?></li>
            </ul>
            <h2 class="graph_title border_title"><span>Last Sync</span></h2>
            <ul class="extra_block">
                <li><span class="label_title">Date:</span><?= $homepageinfo->lastsyncday ?></li>
                <li><span class="label_title">Animals:</span><?= $homepageinfo->numberofsyncedanimals ?></li>
            </ul>
            <h2 class="graph_title border_title"><span>Latest Version</span></h2>
            <ul class="extra_block">
                <li><span class="label_title">IOS:</span>v. <?= $homepageinfo->latestandroidversion ?></li>
                <li><span class="label_title">Android:</span>v. <?= $homepageinfo->latestiosversion ?></li>

            </ul>
        </div>
    </div>
<!--    <h2 class="graph_title border_title"><span>Colony</span></h2>
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
    </div>-->
</div>



<script type="text/javascript">
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Date', 'Hair Loss Assesment ', 'Focal Behavior Observation', 'Novel Object Temperament Test'],
<?php
// print_r();
$graphData = [];
$availableTests = ['alptest', 'bhvtest', 'nvobjtest'];
foreach ($homepageinfo->getAppUsage() as $observations) {

    $graphData[$observations->yr][$observations->mth][$observations->appkey] = $observations->ttl;
}

$minValue = 0;
$maxValue = 0;
if (sizeof($graphData) > 0) {


    foreach ($graphData as $year => $months) {
        foreach ($months as $month => $tests) {
            $totalValues = [];
            foreach ($availableTests as $test) {
                $totalValues[$test] = isset($tests[$test]) ? $tests[$test] : 0;
                $maxValue = $totalValues[$test] > $maxValue ? $totalValues[$test] : $maxValue;
            }

            echo '[new Date(' . $year . ', ' . $month . ', 1), ' . implode(', ', $totalValues) . '],';
        }
    }
} else {
    echo '[new Date('.date('Y').', '.date('n').', '.date('j').')]';
}
?>
        ]);
        var options = {
            /*          curveType: 'function',*/
            legend: {position: 'bottom'},
            colors: ['#f39c12', '#51b0ce', '#ff2f00'],
            width: '100%',
            height: 418,
            chartArea: {left: 40, top: 20, width: '85%'},
            animation: {
                duration: 1000,
                easing: 'inAndOut',
                startup: true
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
    }

</script>
<script type="text/javascript">
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var options = {
            colors: ['#1783a7', '#51b0ce'],
            height: 250,
            chartArea: {left: 0, top: 7, width: '100%', height: 200},
            fontSize: 20,
            legend: {textStyle: {fontSize: 14, bold: false}, position: 'bottom'},
            tooltip: {textStyle: {fontSize: 14, bold: true}},
            animation: {
                duration: 2000,
                easing: 'inAndOut',
                startup: true
            }
        };

        var data = google.visualization.arrayToDataTable([
            ['Examples', 'Percent'],
            ['Test1', 100],
            ['Test2', 0]
        ]);
        var chart = new google.visualization.PieChart(document.getElementById('blue_circle'));

        chart.draw(data, options);

        for (i = 0; i <= 35; i++) {
            (function (index) {
                data1 = [];
                var data1 = google.visualization.arrayToDataTable([
                    ['Examples', 'Percent'],
                    ['Test1', 100 - index],
                    ['Test2', index]
                ]);
                setTimeout(function () {
                    chart.draw(data1, options);
                }, 0 + (i * 40));
            })(i)
        }
    }


</script>
<script type="text/javascript">
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Examples', 'Percent'],
            ['Test1', 45],
            ['Test2', 45],
            ['Test3', 10]
        ]);
        var options = {
            colors: ['#d74613', '#f37112', '#f39c12'],
            height: 250,
            chartArea: {left: 0, top: 7, width: '100%', height: 200},
            fontSize: 20,
            legend: {textStyle: {fontSize: 14, bold: false}, position: 'bottom'},
            tooltip: {textStyle: {fontSize: 14, bold: true}}
        };
        var chart = new google.visualization.PieChart(document.getElementById('orange_circle'));
        chart.draw(data, options);
        for (i = 0; i < 46; i++) {
            (function (index) {
                data1 = [];
                var data1 = google.visualization.arrayToDataTable([
                    ['Examples', 'Percent'],
                    ['Test1', index],
                    ['Test2', index],
                    ['Test3', 100 - 2 * index]
                ]);
                setTimeout(function () {
                    chart.draw(data1, options);
                }, 0 + (i * 40));
            })(i)
        }
    }
</script>
<script type="text/javascript">
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Examples', 'Percent'],
            ['Test1', 45],
            ['Test2', 45],
            ['Test3', 10]
        ]);
        var options = {
            colors: ['#388e3c', '#4db052', '#81c784'],
            height: 250,
            chartArea: {left: 0, top: 7, width: '100%', height: 200},
            fontSize: 20,
            legend: {textStyle: {fontSize: 14, bold: false}, position: 'bottom'},
            tooltip: {textStyle: {fontSize: 14, bold: true}}
        };
        var chart = new google.visualization.PieChart(document.getElementById('green_circle'));
        chart.draw(data, options);
        for (i = 0; i < 46; i++) {
            (function (index) {
                data1 = [];
                var data1 = google.visualization.arrayToDataTable([
                    ['Examples', 'Percent'],
                    ['Test1', index],
                    ['Test2', index],
                    ['Test3', 100 - 2 * index]
                ]);
                setTimeout(function () {
                    chart.draw(data1, options);
                }, 0 + (i * 40));
            })(i)
        }
    }
</script>