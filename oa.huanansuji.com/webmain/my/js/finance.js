function getcashtotal(data) {
    $("#totalmoney").text(data.total);
    var finance = echarts.init(document.getElementById('finance'));
    option = {
        title: {
            text: '资金剩余统计',
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },

        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'value',
            boundaryGap: [0, 0.01]
        },
        yAxis: {
            type: 'category',
            data: data.key
        },
        series: [
            {
                name: '剩余',
                type: 'bar',
                data: data.data,
                label: {
                    normal: {
                        show: true,
                        position: 'inside'
                    }
                },
            }
        ]
    };

    finance.setOption(option);
    window.onresize=sales.resize;
}
