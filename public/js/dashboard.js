async function fetchData() {
    const response = await fetch('/api/data');
    const data = await response.json();
    return data;
}

fetchData().then(data => {
    // D3.js code to create charts
    const width = 800;
    const height = 600;

    const svg = d3.select("#dashboard").append("svg")
        .attr("width", width)
        .attr("height", height);

    // Example: Scatter plot for intensity vs. likelihood
    svg.selectAll("circle")
        .data(data)
        .enter()
        .append("circle")
        .attr("cx", d => d.intensity * 10)
        .attr("cy", d => height - d.likelihood * 10)
        .attr("r", 5)
        .attr("fill", "blue");
});

function updateDashboard() {
    const year = document.getElementById('year').value;
    const topics = document.getElementById('topics').value;

    fetch(`/api/data?year=${year}&topics=${topics}`)
        .then(response => response.json())
        .then(data => {
            // Update charts with filtered data
        });
}

