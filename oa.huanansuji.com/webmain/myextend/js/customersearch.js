/**
 * Created by Administrator on 2018/2/3 0003.
 */
$(function () {
    $("#search").linkbutton({
        onClick: function () {
            $('#dg').datagrid({
                url:"api.php?m=opendkq&openkey="+$.md5("jingdong123456")+"&a=searchCustomers"+"&keywords="+$("#keywords").val(),
                columns: [[
                    {field: 'code', title: 'Code', width: 100},
                    {field: 'tel', title: 'tel', width: 100},
                    {field: 'mobile', title: 'mobile', width: 100},
                    {field: 'email', title: 'email', width: 100},
                    {field: 'whatsapp', title: 'whatsapp', width: 100},
                    {field: 'qq', title: 'qq', width: 100},
                    {field: 'wechat', title: 'wechat', width: 100},
                    {field: 'skype', title: 'skype', width: 100},
                    {field: 'bosstel', title: 'bosstel', width: 100},
                    {field: 'managertel', title: 'managertel', width: 100},
                    {field: 'companytel', title: 'companytel', width: 100},
                    {field: 'salemantel', title: 'salemantel', width: 100},
                    {field: 'desktel', title: 'desktel', width: 100},
                    {field: 'alitm', title: 'alitm', width: 100},
                ]],
                toolbar: '#toobar',
            });

        }
    });
});