/**
 * Created by Administrator on 2018/3/23 0023.
 */
//修改号码
$(function () {
    var host=window.location.host;
    host=host.split(".")[0];
    if(host=='www' || host=='jingdongsuji' || host=='m'){
        $(".phone").text( '18028155516');
    }
    else{
        $(".phone").text( '13925910344');
    }
});