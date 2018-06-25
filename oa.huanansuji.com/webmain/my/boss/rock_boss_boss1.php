

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button onclick="searchout_{rand}()"> 查询支出</button>
        </div>
        <div class="col-md-4">
            <button onclick="searchoutdetail_{rand}()"> 查询支出明细</button>
        </div>
        <div class="col-md-4">
            <button onclick="searchin_{rand}()"> 查询收入</button>
        </div>

        <div class="col-md-12">
            <table width="30%" style="margin-top:5px">
                <tr>
                    <td nowrap>日期</td>
                    <td>
                        <input onclick="js.datechange(this,'date')" style="width:110px" readonly class="form-control datesss" id="dt1_{rand}">
                    </td>
                    <td>至</td>
                    <td>
                        <input onclick="js.datechange(this,'date')" style="width:110px" readonly class="form-control datesss" id="dt2_{rand}">
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-3">
            <div id="main{rand}" style="width:100%;height:400px;"></div>
        </div>
        <div class="col-md-3">
            <div id="main1{rand}" style="width:100%;height:400px;"></div>
        </div>
        <div class="col-md-3">
            <div id="main2{rand}" style="width:100%;height:400px;"></div>
        </div>
        <div id="data">
            <table class="table table-hover">
                <tbody id="tbody{rand}">
                </tbody>
            </table>
        </div>
        <div id="data">
            <table class="table table-hover">
                <tbody id="tbody1{rand}">
                </tbody>
            </table>
        </div>
        <div id="data">
            <table class="table table-hover">
                <tbody id="tbody2{rand}">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        if (month < 10) month = "0" + String(month);
        var day = date.getDate();
        if (day < 10) day = "0" + String(day);
        get('dt1_{rand}').value = String(year) + "-" + String(month) + "-" + "01"
        get('dt2_{rand}').value = String(year) + "-" + String(month) + "-" + day
    })


    function searchout_{rand}() {
        getdata('finceout_main_tbody');

    }
    function searchoutdetail_{rand}() {
        getdata('finceoutdetail_main1_tbody1');

    }
    function searchin_{rand}() {
        getdata('financein_main2_tbody2');

    }


    function getdata(type) {
        typeArr=type.split("_");
        var stardate = get('dt1_{rand}').value
        var enddate = get('dt2_{rand}').value
        $.ajax({
            type: "GET",
            url: "index.php?a=bossDetail&d=my&m=boss&type=" + typeArr[0] + "&startdate=" + stardate + "&enddate=" + enddate,
            dataType: "json",
            success: function (data) {
                $('#'+typeArr[2]+'{rand}').html("");
                var table = "";
                if (data.total > 0) {
                    table = table + "<tr><td colspan='2' align='right'>" + '总金额:' + "</td><td colspan='2'>" + data.total + "</td></tr>"
                    optionText=data.text+'总金额: '+data.total
                }
                else {
                    optionText=data.text
                }
                $.each(data.datatable, function (index, content) {
                    if (index % 2 == 0) {
                        if (index == 0) {
                            table = table + "<tr>";
                        }
                        else {
                            table = table + "</tr><tr>";
                        }

                    }
                    table = table + "<td>" + content.name + "</td><td>" + content.value + "</td>"
                });
                $('#'+typeArr[2]+'{rand}').append(table);
                // 基于准备好的dom，初始化echarts实例
                var myChart = echarts.init(document.getElementById(typeArr[1]+'{rand}'));
                option = {
                    title: {
                        text:optionText,
                        subtext: data.subtext,
                        x: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    series: [
                        {
                            name:data.text,
                            type: 'pie',
                            radius: '55%',
                            center: ['50%', '60%'],
                            data: data.data,
                            itemStyle: {
                                emphasis: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                },
                                normal:{
                                    label:{
                                        show: true,
                                        formatter: '{b} : {c} ({d}%)'
                                    },
                                    labelLine :{show:true}
                                }
                            }
                        }
                    ]
                };

                myChart.setOption(option);
            }
        });

    }


</script>
