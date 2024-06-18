<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .chart-container {
            width: 100%;
            max-width: 1200px;
        }
        #chart, #piechart {
            width: 100%;
            overflow-x: auto;
        }
        .bar:hover {
            fill: orange;
        }
        .tooltip {
            position: absolute;
            text-align: center;
            padding: 6px;
            font: 12px sans-serif;
            background: lightsteelblue;
            border: 0px;
            border-radius: 4px;
            pointer-events: none;
        }
        .axis-label {
            font-size: 14px;
            font-weight: bold;
        }
        .legend {
            font-size: 12px;
        }
        .legend rect {
            stroke-width: 2;
        }
        .arc text {
            font: 10px sans-serif;
            text-anchor: middle;
        }
        .arc path {
            stroke: #fff;
        }
        @media (max-width: 768px) {
            .axis-label {
                font-size: 10px;
            }
            .legend text {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Dashboard</h1>
    <div id="container">
        <div id="chart" class="chart-container"></div>
        <div id="piechart" class="chart-container"></div>
    </div>
    <div id="tooltip" class="tooltip" style="opacity: 0;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/data')
                .then(response => response.json())
                .then(data => {
                    const margin = { top: 20, right: 150, bottom: 100, left: 60 },
                          width = document.querySelector('#chart').clientWidth - margin.left - margin.right,
                          height = 600 - margin.top - margin.bottom;

                    const svg = d3.select("#chart")
                                  .append("svg")
                                  .attr("width", width + margin.left + margin.right)
                                  .attr("height", height + margin.top + margin.bottom)
                                  .append("g")
                                  .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                    const x = d3.scaleBand()
                                .domain(data.map(d => d.topic))
                                .range([0, width])
                                .padding(0.1);

/*                    svg.append("g")
                       .attr("transform", "translate(0," + height + ")")
                       .call(d3.axisBottom(x))
                       .selectAll("text")
                       .attr("transform", "rotate(-65)")
                       .style("text-anchor", "end")
                       .style("font-size", "10px");
 */
                    svg.append("text")
                       .attr("class", "axis-label")
                       .attr("text-anchor", "end")
                       .attr("x", width / 2)
                       .attr("y", height + 30)
                       .text("Topic");

                    const y = d3.scaleLinear()
                                .domain([0, d3.max(data, d => d.intensity)])
                                .nice()
                                .range([height, 0]);

                    svg.append("g")
                       .call(d3.axisLeft(y));

                    svg.append("text")
                       .attr("class", "axis-label")
                       .attr("text-anchor", "end")
                       .attr("x", -height / 2)
                       .attr("y", -40)
                       .attr("dy", ".75em")
                       .attr("transform", "rotate(-90)")
                       .text("Intensity");

                    const sectors = [...new Set(data.map(d => d.sector))];
                    const color = d3.scaleOrdinal()
                                    .domain(sectors)
                                    .range(d3.schemeCategory10);

                    const tooltip = d3.select("#tooltip");

                    svg.selectAll(".bar")
                       .data(data)
                       .enter().append("rect")
                       .attr("class", "bar")
                       .attr("x", d => x(d.topic))
                       .attr("y", d => y(d.intensity))
                       .attr("width", x.bandwidth())
                       .attr("height", d => height - y(d.intensity))
                       .attr("fill", d => color(d.sector))
                       .on("mouseover", function(event, d) {
                           tooltip.transition().duration(200).style("opacity", .9);
                           tooltip.html("Topic: " + d.topic + "<br/>Sector: " + d.sector + "<br/>Intensity: " + d.intensity)
                                  .style("left", (event.pageX + 5) + "px")
                                  .style("top", (event.pageY - 28) + "px");
                       })
                       .on("mouseout", function(d) {
                           tooltip.transition().duration(500).style("opacity", 0);
                       });

                    const legend = svg.selectAll(".legend")
                                      .data(sectors)
                                      .enter().append("g")
                                      .attr("class", "legend")
                                      .attr("transform", (d, i) => "translate(0," + i * 20 + ")");

                    legend.append("rect")
                          .attr("x", width + 20)
                          .attr("width", 18)
                          .attr("height", 18)
                          .style("fill", color);

                    legend.append("text")
                          .attr("x", width + 44)
                          .attr("y", 9)
                          .attr("dy", ".35em")
                          .style("text-anchor", "start")
                          .text(d => d);

                    // Pie chart
                    const pieData = Array.from(d3.group(data, d => d.sector), ([key, value]) => ({ key, value: value.length }));

                    const pieMargin = { top: 20, right: 20, bottom: 20, left: 20 },
                          pieWidth = document.querySelector('#piechart').clientWidth - pieMargin.left - pieMargin.right,
                          pieHeight = 600 - pieMargin.top - pieMargin.bottom,
                          radius = Math.min(pieWidth, pieHeight) / 2;

                    const pieSvg = d3.select("#piechart")
                                     .append("svg")
                                     .attr("width", pieWidth + pieMargin.left + pieMargin.right)
                                     .attr("height", pieHeight + pieMargin.top + pieMargin.bottom)
                                     .append("g")
                                     .attr("transform", "translate(" + (pieWidth / 2 + pieMargin.left) + "," + (pieHeight / 2 + pieMargin.top) + ")");

                    const pie = d3.pie()
                                  .sort(null)
                                  .value(d => d.value);

                    const arc = d3.arc()
                                  .outerRadius(radius)
                                  .innerRadius(0);

                    const g = pieSvg.selectAll(".arc")
                                    .data(pie(pieData))
                                    .enter().append("g")
                                    .attr("class", "arc");

                    g.append("path")
                     .attr("d", arc)
                     .style("fill", d => color(d.data.key));

                    g.append("text")
                     .attr("transform", d => "translate(" + arc.centroid(d) + ")")
                     .attr("dy", ".35em")
                     .text(d => d.data.key);
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
        });
    </script>
</body>
</html>

