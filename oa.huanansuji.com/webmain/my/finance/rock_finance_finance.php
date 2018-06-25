<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/30 0030
 * Time: 上午 10:37
 */
?>
<button onclick="loadata___aaaaaaaa()"> 刷新数据</button>
<div id="data_afddsfdfsdfsdfsdsdsdsdfsdsdsdsdsdsdsdsdfsdfsdfsdsdsddsfdsfsd">  </div>


<script>

    loadata___aaaaaaaa()
    function loadata___aaaaaaaa() {
        $.ajax({
            type: "GET",
            url: "index.php?a=getWenyuanFinance&d=my&m=finance",
            dataType: "html",
            success: function(data){
                $('#data_afddsfdfsdfsdfsdsdsdsdfsdsdsdsdsdsdsdsdfsdfsdfsdsdsddsfdsfsd').html(data)
            }
        });
    }


</script>
