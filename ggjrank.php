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
		path.slice{
			stroke-width:2px;
		}

		polyline{
			opacity: .3;
			stroke: black;
			stroke-width: 2px;
			fill: none;
		}
    </style>
</head>

<body>
    <script type="text/javascript">
	var data;
	var total_skills;
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
            data = dataP;
	
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
						
			total_skills = [
			{name: "2d Art", skill:"s_2d_art",jammers:0},
			{name: "3d Art", skill:"s_3d_art",jammers:0},
			{name: "Animation", skill:"s_animation",jammers:0},
			{name: "Audio", skill:"s_audio",jammers:0},
			{name: "Game Design", skill:"s_game_design",jammers:0},
			{name: "Game Development", skill:"s_game_development",jammers:0},
			{name: "Music", skill:"s_music",jammers:0},
			{name: "Programming", skill:"s_programming",jammers:0},
			{name: "Hardware", skill:"s_hardware",jammers:0},
			{name: "Project Management", skill:"s_project_management",jammers:0},
			{name: "QA", skill:"s_quality_assurance",jammers:0},
			{name: "Marketing", skill:"s_marketing",jammers:0},
			{name: "Story and Narrative", skill:"s_story_and_narrative",jammers:0},
			{name: "Web Design", skill:"s_web_design",jammers:0},
			{name: "Writing", skill:"s_writing",jammers:0}];
				   
            for( var k in data){
				total_skills[0].jammers +=  data[k]["s_2d_art"];
				total_skills[1].jammers +=  data[k]["s_3d_art"];
				total_skills[2].jammers +=  data[k]["s_animation"];
				total_skills[3].jammers +=  data[k]["s_audio"];
				total_skills[4].jammers +=  data[k]["s_game_design"];
				total_skills[5].jammers +=  data[k]["s_game_development"];
				total_skills[6].jammers +=  data[k]["s_music"];
				total_skills[7].jammers +=  data[k]["s_programming"];
				total_skills[8].jammers +=  data[k]["s_hardware"];
				total_skills[9].jammers +=  data[k]["s_project_management"];
				total_skills[10].jammers +=  data[k]["s_quality_assurance"];
				total_skills[11].jammers +=  data[k]["s_marketing"];
				total_skills[12].jammers +=  data[k]["s_story_and_narrative"];
				total_skills[13].jammers +=  data[k]["s_web_design"];
				total_skills[14].jammers += data[k]["s_writing"];
			}
			var total_skills_str = "";
			for( var k in total_skills){
				var s = total_skills[k];
				total_skills_str += s.name + ':' + s.jammers + "   ";
			}
			
			
            console.log(data);
            //d3.json(rawdata, function(data) {
	       /* var skillsvg = d3.select("body").append("div");
			skillsvg.html(   
 						total_skills_str
			);
			*/
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
                .text("GGJ 2017 UK Registered jammers")
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
			makePie();
            change(total_skills);
        })();
		function calcpercent(datum){
			 var total = 0;
			 for( var k in datum){
				total += datum[k].jammers;
			 }
			 for( var k in datum){
				datum[k].percent = (datum[k].jammers / total) * 100.0;
			 }
		}
		
		var pisvg;
		var pie;
		var pikey;
		var c20b;
		var piarc,piouterArc,piwidth,piheight,piradius;
		var centerText;
		function makePie(){
			c20b = d3.scale.category20b();
			piwidth = 960;
			piheight = 450;
			piradius = Math.min(piwidth, piheight) / 2;
			pisvg = d3.select("body")
				.append("svg").style("width", "100%").style("height", piheight+"px") 
				.append("g")

			pisvg.append("g")
				.attr("class", "slices");
			pisvg.append("g")
				.attr("class", "labels");
			pisvg.append("g")
				.attr("class", "lines");



			pie = d3.layout.pie()
				.sort(null)
				.value(function(d) {
					return d.jammers;
				});

			piarc = d3.svg.arc()
				.outerRadius(piradius * 0.8)
				.innerRadius(piradius * 0.4);

			piouterArc = d3.svg.arc()
				.innerRadius(piradius * 0.9)
				.outerRadius(piradius * 0.9);

			pisvg.attr("transform", "translate(" + piwidth / 2 + "," + piheight / 2 + ")");

			pikey = function(d){ 
			return d.data.name; 
			};
			centerText = pisvg.append("text");
			centerText.attr("text-anchor","middle");
			centerText.attr("dy", ".35em");
			centerText.text(function(d) {
				return "Total Uk Jammer Skills";
			});
		}
		
		function change(data) {
			/* ------- PIE SLICES -------*/
			var slice = pisvg.select(".slices").selectAll("path.slice")
				.data(pie(data), pikey);

			slice.enter()
				.insert("path")
				.style("fill", function(d,i) { return c20b(i); })
				.attr("class", "slice");

			slice		
				.transition().duration(1000)
				.attrTween("d", function(d) {
					this._current = this._current || d;
					var interpolate = d3.interpolate(this._current, d);
					this._current = interpolate(0);
					return function(t) {
						return piarc(interpolate(t));
					};
				})

			slice.exit()
				.remove();

			/* ------- TEXT LABELS -------*/
			calcpercent(data);
			var text = pisvg.select(".labels").selectAll("text")
				.data(pie(data), pikey);

			text.enter()
				.append("text")
				.attr("dy", ".35em")
				.text(function(d) {
					return d.data.name + ' '+ d.data.jammers;
				});
			
			function midAngle(d){
				return d.startAngle + (d.endAngle - d.startAngle)/2;
			}

			text.transition().duration(1000)
				.attrTween("transform", function(d) {
					this._current = this._current || d;
					var interpolate = d3.interpolate(this._current, d);
					this._current = interpolate(0);
					return function(t) {
						var d2 = interpolate(t);
						var pos = piouterArc.centroid(d2);
						//pos[0] = piradius * (midAngle(d2) < Math.PI ? 1 : -1);
						//pos[0] *= 0.8;
						return "translate("+ pos +")";
					};
				})
				.styleTween("text-anchor", function(d){
					this._current = this._current || d;
					var interpolate = d3.interpolate(this._current, d);
					this._current = interpolate(0);
					return function(t) {
						var d2 = interpolate(t);
						return midAngle(d2) < Math.PI ? "start":"end";
					};
				});

			text.exit().remove();

			/* ------- SLICE TO TEXT POLYLINES -------*/

			var polyline = pisvg.select(".lines").selectAll("polyline")
				.data(pie(data), pikey);
			
			polyline.enter()
				.append("polyline");

			polyline.transition().duration(1000)
				.attrTween("points", function(d){
					this._current = this._current || d;
					var interpolate = d3.interpolate(this._current, d);
					this._current = interpolate(0);
					return function(t) {
						var d2 = interpolate(t);
						//var pos = piouterArc.centroid(d2);
						//pos[0] = piradius * 0.95 * (midAngle(d2) < Math.PI ? 1 : -1);
						return [piarc.centroid(d2), piouterArc.centroid(d2)];//, pos];
					};			
				});
			
			polyline.exit()
				.remove();
		};
		
    </script>
</body>

</html>