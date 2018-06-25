<div class="container">
    <div class="row">
        <div class="col-md-6">
            <form role="form">
                <div class="form-group">
                    <select class="form-control" id="select{rand}">
                        <option value="bossknow_main_tbody">老板表</option>
                        <option value="financedetail_main_tbody">供应商欠款</option>
                        <option value="supplierrecent_main_tbody">购买查询(日期)</option>
                        <option value="contract_main_tbody">合同(日期)</option>
                        <option value="duein_main_tbody">待收金额</option>
                        <option value="allds_main_tbody">所有待收明细</option>
                        <option value="pastduein_main_tbody">过期待收</option>
                        <option value="allpastds_main_tbody">过期待收明细</option>
                        <option value="pastdueinnotfinish_main_tbody">过期待收未完成</option>
                        <option value="allpastdsnotfinish_main_tbody">过期待收未完成明细</option>
                        <option value="pastfinish_main_tbody">完成未收</option>
                        <option value="pastfinishdetail_main_tbody">完成未收明细</option>
                        <option value="finceout_main_tbody">所有支出(日期)</option>
                        <option value="finceoutdetail_main_tbody">支出明细(日期)</option>
                        <option value="financein_main_tbody">所有收入(日期)</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <button onclick="search_{rand}()"> 查询</button>
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
        <div class="col-md-12">
            <div id="main{rand}" style="width:100%;height:800px;"></div>
        </div>
        <div id="data">
            <table class="table table-hover">
                <tbody id="tbody{rand}">
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
        search_{rand}();
        $("#select{rand}").change(function () {
            getdata($('#select{rand}').val());
        })

    })


    function search_{rand}() {
        getdata($('#select{rand}').val());
    }

    function getdata(type) {
        typeArr=type.split("_");
        var stardate = get('dt1_{rand}').value
        var enddate = get('dt2_{rand}').value
        optionText=''
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
                    table = table + "<td onclick='searcitemdetail_{rand}(content.name)'>" + content.name + "</td><td>" + content.value + "</td>"
                });
                $('#'+typeArr[2]+'{rand}').append(table);
                // 基于准备好的dom，初始化echarts实例
                var myChart = echarts.init(document.getElementById(typeArr[1]+'{rand}'));


                option = {
                    title: {
                        text: optionText,
                        subtext: data.subtext,
                        x: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    series: [
                        {
                            name: data.text,
                            type: 'pie',
                            radius: '30%',
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
