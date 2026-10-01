<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="panel h-100">
            <h6 class="mb-3">By Status</h6>
            <div style="position: relative; height: 260px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel h-100">
            <h6 class="mb-3">By Department</h6>
            <div style="position: relative; height: 260px;">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel h-100">
            <h6 class="mb-3">By Priority</h6>
            <div style="position: relative; height: 260px;">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel h-100">
            <h6 class="mb-3">Last 6 Months</h6>
            <div style="position: relative; height: 260px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
</div>


<script>
(function () {
    const statusData = @json($byStatus);
    const deptData = @json($byDepartment);
    const priorityData = @json($byPriority);
    const trendData = @json($monthlyTrend);

    function makeChart(id, type, labels, values, colors) {
        const ctx = document.getElementById(id);
        if (!ctx) return;
        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{ data: values, backgroundColor: colors }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    makeChart('statusChart', 'doughnut', Object.keys(statusData), Object.values(statusData),
        ['#f59e0b','#3b82f6','#06b6d4','#22c55e','#64748b','#ef4444']);

    makeChart('departmentChart', 'bar', Object.keys(deptData), Object.values(deptData),
        '#1e293b');

    makeChart('priorityChart', 'doughnut', Object.keys(priorityData), Object.values(priorityData),
        ['#ef4444','#f59e0b','#64748b']);

    makeChart('trendChart', 'line', Object.keys(trendData), Object.values(trendData),
        '#3b82f6');
})();
</script>