<?php
error_reporting(E_ALL);
if(!is_null($_POST['newdata']) &&  strlen ($_POST['newdata']) > 0 ){
	echo $_POST['newdata'];
	$myfile = fopen("ggjsites.json", "w+") or die("Unable to open file!");
	fwrite($myfile, urldecode ( $_POST['newdata']));
	exit();
}
$myfile = fopen("ggjsites.json", "r") or die("Unable to open file!");
//echo (fread($myfile,filesize("ggjsites.json")));
?>


<!DOCTYPE html>
<html>
<?php error_reporting(E_ALL); if(!is_null($_POST[ 'newdata']) && strlen ($_POST[ 'newdata'])> 0 ){ echo $_POST['newdata']; $myfile = fopen("ggjsites.json", "w+") or die("Unable to open file!"); fwrite($myfile, urldecode ( $_POST['newdata'])); exit(); } $myfile = fopen("ggjsites.json", "r") or die("Unable to open file!"); //echo (fread($myfile,filesize("ggjsites.json"))); ?>


<!DOCTYPE html>
<html>

<head>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <style type="text/css">
		body
		{
			background: #5fe8f7;
			font-family: 'Roboto', sans-serif;
		}
        .x-axis path {
            stroke: black;
            fill: none;
        }
        .x-axis line {
			stroke: black;
            fill: none;
            stroke: none;
            stroke-opacity: .8;
            shape-rendering: crispEdges;
        }
        .bars rect {
            fill: #ffff33;
            fill-opacity: .9;
        }
        .title {
            font-weight: bold;
        }
        #precise-value {
            fill: black;
            font-size: 12px;
        }
		a {
			text-decoration: underline;
		}
    </style>
</head>

<body>
    <script type="text/javascript">
        (function() { 
<?php
	echo("var rawdata = '".fread($myfile, filesize("ggjsites.json"))."';");
?>

            var margin = {
                top: 50,
                bottom: 100,
                left: ($(window).width()*0.27),
                right: 100
            };
            var width = $(window).width() - margin.left - margin.right;
            var height = ($(window).height()*0.6) - margin.top - margin.bottom;

            var xScale = d3.scale.linear().range([0, width]);
            var yScale = d3.scale.ordinal().rangeRoundBands([0, height], 1.8, 0);

            var numTicks = 5;
            var xAxis = d3.svg.axis().scale(xScale)
                .orient("top")
                .tickSize((-height))
                .ticks(numTicks);

            var svg = d3.select("body").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .attr("class", "base-svg");

            var barSvg = svg.append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
                .attr("class", "bar-svg");

            var x = barSvg.append("g")
                .attr("class", "x-axis");

            var url = "data.json";
            var dataP = JSON.parse(rawdata);
            var data = dataP;
	
           /* for (var key in dataP) {
                data.push({
                    jamsite: key,
                    jammers: parseInt(dataP[key])
                });
            }*/
			data = data.filter(function(d) { return d.jammers > 1 });
            data.sort(function(a, b) {
                return b.jammers - a.jammers
            });
            console.log(data);
            //d3.json(rawdata, function(data) {

            var xMax = d3.max(data, function(d) {
                return d.jammers;
            });
            var xMin = 0;
            xScale.domain([xMin, xMax]);
            yScale.domain(data.map(function(d) {
                return d.jamsite;
            }));

            d3.select(".base-svg").append("text")
                .attr("x", margin.left)
                .attr("y", (margin.top) / 2)
                .attr("text-anchor", "start")
                .text("GGJ 2017 Registered jammers")
                .attr("class", "title");

            var groups = barSvg.append("g").attr("class", "labels")
                .selectAll("text")
                .data(data)
                .enter()
                .append("g");

            var tt = groups
			.append("svg:a").attr("xlink:href", function(d) {
                    return d.url;
             })
			.append("text")
                .attr("x", "0")
                .attr("y", function(d) {
                    return yScale(d.jamsite);
                })
                .text(function(d) {
                    return d.jamsite;
                })

                .attr("text-anchor", "end")
                .attr("dy", ".9em")
                .attr("dx", "-.32em")
                .attr("id", function(d, i) {
                    return "label" + i;
                });

            var bars = groups
                .attr("class", "bars")
                .append("rect")
                .attr("width", function(d) {
                    return xScale(d.jammers);
                })
                .attr("height", height / data.length)
                .attr("x", xScale(xMin))
                .attr("y", function(d) {
                    return yScale(d.jamsite);
                })
                .attr("id", function(d, i) {
                    return "bar" + i;
                });

            groups.append("text")
                .attr("x", function(d) {
                    return xScale(d.jammers);
                })
                .attr("y", function(d) {
                    return yScale(d.jamsite);
                })
                .text(function(d) {
                    return d.jammers;
                })
                .attr("text-anchor", "end")
                .attr("dy", "1.2em")
                .attr("dx", "-.32em")
                .attr("id", "precise-value");

            bars
                .on("mouseover", function() {
                    var currentGroup = d3.select(this.parentNode);
                    currentGroup.select("rect").style("fill", "#3399cc");
                    currentGroup.select("text").style("font-weight", "bold");
                })
                .on("mouseout", function() {
                    var currentGroup = d3.select(this.parentNode);
                    currentGroup.select("rect").style("fill", "#ffff33");
                    currentGroup.select("text").style("font-weight", "normal");
                });

			tt
                .on("mouseover", function() {
                    var currentGroup = d3.select(this.parentNode);
                    currentGroup.select("rect").style("fill", "#3399cc");
                    currentGroup.select("text").style("font-weight", "bold");
                })
                .on("mouseout", function() {
                    var currentGroup = d3.select(this.parentNode);
                    currentGroup.select("rect").style("fill", "#ffff33");
                    currentGroup.select("text").style("font-weight", "normal");
                });
				
            x.call(xAxis);
            var grid = xScale.ticks(numTicks);
            barSvg.append("g").attr("class", "grid")
                .selectAll("line")
                .data(grid, function(d) {
                    return d;
                })
                .enter().append("line")
                .attr("y1", 0)
                .attr("y2", height + margin.bottom)
                .attr("x1", function(d) {
                    return xScale(d);
                })
                .attr("x2", function(d) {
                    return xScale(d);
                })
                .attr("stroke", "black");

            //});

        })();
    </script>
</body>

</html>