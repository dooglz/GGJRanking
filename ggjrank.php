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
	<?php
	echo("var rawdata = '".fread($myfile, filesize("ggjsites.json"))."';");
?>
        (function() { 
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
						
			total_skills = {name:"Total Uk Jammer Skills",  skills:{}, visdata:[]};
				   
            for( var k in data){
				for( var skill in data[k].skills){
					if(total_skills.skills[skill] == undefined){total_skills.skills[skill] = 0;} 
					total_skills.skills[skill] += data[k].skills[skill];
				}
			}
			for(var k in total_skills.skills){
				total_skills.visdata.push({name:k, value:total_skills.skills[k]});
			}
				for( var k in data){
				
				//data[k].total_skills = {name: data[k].jamsite,  skills:[
				//	{name: "2d Art", skill:"s_2d_art",jammers:0},
				//	{name: "3d Art", skill:"s_3d_art",jammers:0},
				//	{name: "Animation", skill:"s_animation",jammers:0},
				//	{name: "Audio", skill:"s_audio",jammers:0},
				//	{name: "Game Design", skill:"s_game_design",jammers:0},
				//	{name: "Game Development", skill:"s_game_development",jammers:0},
				//	{name: "Music", skill:"s_music",jammers:0},
				//	{name: "Programming", skill:"s_programming",jammers:0},
				//	{name: "Hardware", skill:"s_hardware",jammers:0},
				//	{name: "Project Management", skill:"s_project_management",jammers:0},
				//	{name: "QA", skill:"s_quality_assurance",jammers:0},
				//	{name: "Marketing", skill:"s_marketing",jammers:0},
				//	{name: "Story and Narrative", skill:"s_story_and_narrative",jammers:0},
				//	{name: "Web Design", skill:"s_web_design",jammers:0},
				//	{name: "Writing", skill:"s_writing",jammers:0}
				//]};

				//data[k].total_skills.skills[0].jammers =  data[k]["s_2d_art"];
				//data[k].total_skills.skills[1].jammers =  data[k]["s_3d_art"];
				//data[k].total_skills.skills[2].jammers =  data[k]["s_animation"];
				//data[k].total_skills.skills[3].jammers =  data[k]["s_audio"];
				//data[k].total_skills.skills[4].jammers =  data[k]["s_game_design"];
				//data[k].total_skills.skills[5].jammers =  data[k]["s_game_development"];
				//data[k].total_skills.skills[6].jammers =  data[k]["s_music"];
				//data[k].total_skills.skills[7].jammers =  data[k]["s_programming"];
				//data[k].total_skills.skills[8].jammers =  data[k]["s_hardware"];
				//data[k].total_skills.skills[9].jammers =  data[k]["s_project_management"];
				//data[k].total_skills.skills[10].jammers =  data[k]["s_quality_assurance"];
				//data[k].total_skills.skills[11].jammers =  data[k]["s_marketing"];
				//data[k].total_skills.skills[12].jammers =  data[k]["s_story_and_narrative"];
				//data[k].total_skills.skills[13].jammers =  data[k]["s_web_design"];
				//data[k].total_skills.skills[14].jammers = data[k]["s_writing"];
				
				//total_skills.skills[0].jammers +=  data[k]["s_2d_art"];
				//total_skills.skills[1].jammers +=  data[k]["s_3d_art"];
				//total_skills.skills[2].jammers +=  data[k]["s_animation"];
				//total_skills.skills[3].jammers +=  data[k]["s_audio"];
				//total_skills.skills[4].jammers +=  data[k]["s_game_design"];
				//total_skills.skills[5].jammers +=  data[k]["s_game_development"];
				//total_skills.skills[6].jammers +=  data[k]["s_music"];
				//total_skills.skills[7].jammers +=  data[k]["s_programming"];
				//total_skills.skills[8].jammers +=  data[k]["s_hardware"];
				//total_skills.skills[9].jammers +=  data[k]["s_project_management"];
				//total_skills.skills[10].jammers +=  data[k]["s_quality_assurance"];
				//total_skills.skills[11].jammers +=  data[k]["s_marketing"];
				//total_skills.skills[12].jammers +=  data[k]["s_story_and_narrative"];
				//total_skills.skills[13].jammers +=  data[k]["s_web_design"];
				//total_skills.skills[14].jammers += data[k]["s_writing"];
			}
			var total_skills_str = "";
			for( var k in total_skills){
				var s = total_skills[k];
				total_skills_str += s + ':' + s.jammers + "   ";
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
                })
				.on("click", function(element) {
                    var currentGroup = d3.select(this.parentNode);
					console.log(element);
					 change(element.total_skills.visdata);
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
            change(total_skills.visdata);
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
		var piCol
		function makePie(){
			c20b = d3.scale.category20b();
			piCol = d3.scale.ordinal()
			.domain(["s_2d_art","s_3d_art","s_animation","s_audio","s_game_design","s_game_development","s_music",
					"s_programming","s_hardware","s_project_management","s_quality_assurance","s_marketing",
					"s_story_and_narrative","s_web_design","s_writing"])
			.range(["#ff9000", "#ff0000", "#e87e33", "#edc73d", 
					"#33e8ba", "#00ff00", "#efef26", "#4adb62",
					"#51e2b9", "#deefea", "#dbd2b6", "#d2b6db",
					"#0095f2", "#00f2c9", "#2dafe2"]);

			
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
			return d.data; 
			};
			centerText = pisvg.append("text");
			centerText.attr("text-anchor","middle");
			centerText.attr("dy", ".35em");
			centerText.text(function(d) {
				return "Total Uk Jammer Skills";
			});
		}
		function mergeWithFirstEqualZero(first, second){
			var secondSet = d3.set(); second.forEach(function(d) { secondSet.add(d.label); });

			var onlyFirst = first
				.filter(function(d){ return !secondSet.has(d.label) })
				.map(function(d) { return {label: d.label, value: 0}; });
			return d3.merge([ second, onlyFirst ])
				.sort(function(a,b) {
					return d3.ascending(a.label, b.label);
				});
		}
		
		function change(data) {
			centerText.text(function(d) {
				return data.name;
			}).call(wrap, 160);
			var data0 = pisvg.select(".slices").selectAll("path.slice").data().map(function(d) { 
			console.log(d);
			return d.data 
			});
			if (data0 == undefined || data0.length == 0) data0 = data;
			var was = mergeWithFirstEqualZero(data, data0);
			var is = mergeWithFirstEqualZero(data0, data);
			/* ------- PIE SLICES -------*/
			var slice = pisvg.select(".slices").selectAll("path.slice")
				.data(pie(data), pikey);

			slice.enter()
				.insert("path")
				.style("fill", function(d,i) { console.log(d.data.value); return piCol(d.data.name); })
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
				.style("opacity", 0)
				.text(function(d) {
					return d.data.name + ' '+ d.data.jammers;
				});
			
			function midAngle(d){
				return d.startAngle + (d.endAngle - d.startAngle)/2;
			}
			text = pisvg.select(".labels").selectAll("text").data(pie(is), pikey);
		
			text.transition().duration(1000)
				.style("opacity", function(d) {
					return d.data.jammers == 0 ? 0 : 1;
				})
				.text(function(d) {
					return d.data.name + ' '+ d.data.jammers;
				})
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
				text = pisvg.select(".labels").selectAll("text")
						.data(pie(data), pikey);
		
			text.exit().remove();

			/* ------- SLICE TO TEXT POLYLINES -------*/

			var polyline = pisvg.select(".lines").selectAll("polyline")
				.data(pie(data), pikey);
			
			polyline.enter()
				.append("polyline")
				.style("opacity", 0)
				.each(function(d) {
					this._current = d;
				});

			polyline = pisvg.select(".lines").selectAll("polyline").data(pie(is), pikey);
			
			polyline.transition().duration(1000)
				.style("opacity", function(d) {
					return d.data.jammers == 0 ? 0 : .5;
				})
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
				
			polyline = pisvg.select(".lines").selectAll("polyline").data(pie(data), pikey);
			polyline.exit()
				.remove();
		};
		function wrap(text, width) {
		  text.each(function() {
			var text = d3.select(this),
				words = text.text().split(/\s+/).reverse(),
				word,
				line = [],
				lineNumber = 0,
				lineHeight = 0.6, // ems
				y = text.attr("y"),
				dy = parseFloat(text.attr("dy")),
				tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
			while (word = words.pop()) {
			  line.push(word);
			  tspan.text(line.join(" "));
			  if (tspan.node().getComputedTextLength() > width) {
				line.pop();
				tspan.text(line.join(" "));
				line = [word];
				tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
			  }
			}
		  });
		}
    </script>
</body>

</html>