var host=window.location.host;
host=host.split(".")[0];
var moblielink='';
if(host=='www'|| host=='jingdongsuji'){
    moblielink="http://m.jingdongsuji.com";
}
else {
    moblielink="http://p.jingdongsuji.com";
}
if (window.location.toString().indexOf('pref=padindex') != -1) {
} else {
    if (/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))) {
        if (window.location.href.indexOf("?mobile") < 0) {
            try {
                if (/Android|Windows Phone|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)) {

                    window.location.href = moblielink;
                } else if (/iPad/i.test(navigator.userAgent)) {
                } else {
                }
            } catch (e) {
            }
        }
    }
}