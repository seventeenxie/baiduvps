<?php
/**
*	模块：finycl.购买原材料，
*	说明：自定义区域内可写您想要的代码，模块列表页面，生成分为2块
*	来源：流程模块→表单元素管理→[模块.购买原材料]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'finycl',modename='购买原材料',isflow=1,modeid='75',atype = params.atype,pnum=params.pnum;
	if(!atype)atype='';if(!pnum)pnum='';
    var fieldsarr = [{"name":"\u7533\u8bf7\u4eba","fields":"base_name"},{"name":"\u7533\u8bf7\u4eba\u90e8\u95e8","fields":"base_deptname"},{"name":"\u5355\u53f7","fields":"sericnum"},{"fields":"code","name":"\u7f16\u53f7","fieldstype":"num","ispx":"1","isalign":"0","islb":"1"},{"fields":"money","name":"\u603b\u8d27\u6b3e","fieldstype":"number","ispx":"1","isalign":"0","islb":"1"},{"fields":"oncredit","name":"\u8d4a\u8d26\u91d1\u989d","fieldstype":"number","ispx":"1","isalign":"0","islb":"0"},{"fields":"paytype2","name":"\u4ed8\u6b3e\u65b9\u5f0f","fieldstype":"select","ispx":"0","isalign":"0","islb":"0"},{"fields":"tempdate","name":"\u5355\u636e\u53d1\u751f\u65e5\u671f","fieldstype":"date","ispx":"1","isalign":"0","islb":"1"},{"fields":"returnmoney","name":"\u8fd8\u6b3e\u91d1\u989d","fieldstype":"number","ispx":"0","isalign":"0","islb":"1"},{"fields":"invoice","name":"\u5e94\u6536\u53d1\u7968\u91d1\u989d","fieldstype":"number","ispx":"1","isalign":"0","islb":"1"},{"fields":"dsinvoice","name":"\u5f85\/\u9884\u6536\u53d1\u7968","fieldstype":"hidden","ispx":"1","isalign":"0","islb":"1"},{"fields":"reincoice","name":"\u6536\u5230\u53d1\u7968","fieldstype":"number","ispx":"0","isalign":"0","islb":"1"},{"fields":"perinvoice","name":"\u62b5\u6263\u7cfb\u6570","fieldstype":"select","ispx":"0","isalign":"0","islb":"1"},{"fields":"explain","name":"\u8bf4\u660e","fieldstype":"textarea","ispx":"0","isalign":"0","islb":"1"},{"fields":"type","name":"\u7c7b\u578b","fieldstype":"fixed","ispx":"0","isalign":"0","islb":"0"},{"fields":"applydt","name":"\u5f55\u5165\u65e5\u671f","fieldstype":"fixed","ispx":"1","isalign":"0","islb":"1"},{"fields":"paytype","name":"\u4ed8\u6b3e\u65b9\u5f0f","fieldstype":"select","ispx":"1","isalign":"0","islb":"1"},{"fields":"supplier","name":"\u4f9b\u5e94\u5546","fieldstype":"selectdatafalse","ispx":"1","isalign":"0","islb":"1"},{"fields":"paydt","name":"\u8d2d\u4e70\u65e5\u671f","fieldstype":"date","ispx":"1","isalign":"0","islb":"1"},{"fields":"goodstype","name":"\u539f\u6750\u6599\u7c7b\u578b","fieldstype":"text","ispx":"1","isalign":"0","islb":"1"}],fieldsselarr= {"columns_finycl_":"code,dsinvoice,explain,paytype,supplier,paydt,goodstype,money,oncredit,invoice,caozuo"};

    var c = {
		reload:function(){
			a.reload();
		},
		clickwin:function(o1,lx){
			var id=0;
			if(lx==1)id=a.changeid;
			openinput(modename,modenum,id,'opegs{rand}');
		},
		view:function(){
			var d=a.changedata;
			openxiangs(modename,modenum,d.id,'opegs{rand}');
		},
		searchbtn:function(){
			this.search({});
		},
		search:function(cans){
			var s=get('key_{rand}').value,zt='';
			if(get('selstatus_{rand}'))zt=get('selstatus_{rand}').value;
			var canss = js.apply({key:s,keystatus:zt}, cans);
			a.setparams(canss,true);
		},
		//高级搜索
		searchhigh:function(){
			new highsearchclass({
				modenum:modenum,
				oncallback:function(d){
					c.searchhighb(d);
				}
			});
		},
		searchhighb:function(d){
			d.key='';
			get('key_{rand}').value='';
			a.setparams(d,true);
		},
		//导出
		daochu:function(o1,lx,lx1,e){
			if(!this.daochuobj)this.daochuobj=$.rockmenu({
				width:120,top:35,donghua:false,data:[],
				itemsclick:function(d, i){
					c.daonchuclick(d);
				}
			});
			var d = [{name:'导出全部',lx:0},{name:'导出当前页',lx:1},{name:'订阅此列表',lx:2}];
			this.daochuobj.setData(d);
			var lef = $(o1).offset();
			this.daochuobj.showAt(lef.left, lef.top+35);
		},
		daonchuclick:function(d){
			if(d.lx==0)a.exceldown();
			if(d.lx==1)a.exceldownnow();
			if(d.lx==2)this.subscribelist();
		},
		subscribelist:function(){
			js.subscribe({
				title:'购买原材料('+nowtabs.name+')',
				cont:'购买原材料('+nowtabs.name+')的列表的',
				explain:'订阅[购买原材料]的列表',
				objtable:a
			});
		},
		getacturl:function(act){
			return js.getajaxurl(act,'mode_finycl|input','flow',{'modeid':modeid});
		},
		changatype:function(o1,lx){
			$("button[id^='changatype{rand}']").removeClass('active');
			$('#changatype{rand}_'+lx+'').addClass('active');
			a.setparams({atype:lx},true);
			nowtabssettext($(o1).html());
		},
		init:function(){
			$('#key_{rand}').keyup(function(e){
				if(e.keyCode==13)c.searchbtn();
			});
			this.initpage();
		},
		initpage:function(){
			
		},
		loaddata:function(d){
			if(!d.atypearr)return;
			get('addbtn_{rand}').disabled=(d.isadd!=true);
			if(d.isdaoru)$('#daoruspan_{rand}').show();
			var d1 = d.atypearr,len=d1.length,i,str='';
			for(i=0;i<len;i++){
				str+='<button class="btn btn-default" click="changatype,'+d1[i].num+'" id="changatype{rand}_'+d1[i].num+'" type="button">'+d1[i].name+'</button>';
			}
			$('#changatype{rand}').html(str);
			$('#changatype{rand}_'+atype+'').addClass('active');
			js.initbtn(c);
		},
		setcolumns:function(fid, cnas){
			var d = false,i,ad=bootparams.columns,len=ad.length,oi=-1;
			for(i=0;i<len;i++){
				if(ad[i].dataIndex==fid){
					d = ad[i];
					oi= i;
					break;
				}
			}
			if(d){
				d = js.apply(d, cnas);
				bootparams.columns[oi]=d;
			}
		},
		daoru:function(){
			window.managelistfinycl = a;
			addtabs({num:'daorufinycl',url:'flow,input,daoru,modenum=finycl',icons:'plus',name:'导入购买原材料'});
		},
		initcolumns:function(bots){
			var num = 'columns_'+modenum+'_'+pnum+'',d=[],d1,d2={},i,len=fieldsarr.length,bok;
			var nstr= fieldsselarr[num];if(!nstr)nstr='';
			if(nstr)nstr=','+nstr+',';
			if(nstr=='' && isflow==1){
				d.push({text:'申请人',dataIndex:'base_name',sortable:true});
				d.push({text:'申请人部门',dataIndex:'base_deptname',sortable:true});
			}
			for(i=0;i<len;i++){
				d1 = fieldsarr[i];
				bok= false;
				if(nstr==''){
					if(d1['islb']=='1')bok=true;
				}else{
					if(nstr.indexOf(','+d1.fields+',')>=0)bok=true;
				}
				if(bok){
					d2={text:d1.name,dataIndex:d1.fields};
					if(d1.ispx=='1')d2.sortable=true;
					if(d1.isalign=='1')d2.align='left';
					if(d1.isalign=='2')d2.align='right';
					d.push(d2);
				}
			}
			if(isflow==1)d.push({text:'状态',dataIndex:'statustext'});
			if(nstr=='' || nstr.indexOf(',caozuo,')>=0)d.push({text:'',dataIndex:'caozuo',callback:'opegs{rand}'});
			if(!bots){
				bootparams.columns=d;
			}else{
				a.setColumns(d);
			}
		},
		setparams:function(cs){
			var ds = js.apply({},cs);
			a.setparams(ds);
		},
		storeurl:function(){
			var url = this.getacturl('publicstore')+'&pnum='+pnum+'';
			return url;
		},
		printlist:function(){
			js.msg('success','可使用导出，然后打开在打印');
		},
		getbtnstr:function(txt, click, ys, ots){
			if(!ys)ys='default';
			if(!ots)ots='';
			return '<button class="btn btn-'+ys+'" id="btn'+click+'_{rand}" click="'+click+'" '+ots+' type="button">'+txt+'</button>';
		},
		setfieldslist:function(){
			new highsearchclass({
				modenum:modenum,
				modeid:modeid,
				type:1,
				isflow:isflow,
				pnum:pnum,atype:atype,
				fieldsarr:fieldsarr,
				fieldsselarr:fieldsselarr,
				oncallback:function(str){
					fieldsselarr[this.columnsnum]=str;
					c.initcolumns(true);
					c.reload();
				}
			});
		},
        totalmoney:function () {
		    d=this.check_select()
            var len = d.length;
            money=0
            for(var i=0;i<len;i++){
                data=d[i]
                money=money+parseFloat(data.money)
            }
            $("#total_money").text(parseInt(money).toString())
        },
        tototal_own:function(){
            d=this.check_select()
            var len = d.length;
            money=0
            for(var i=0;i<len;i++){
                data=d[i]
                if( isNumber(data.oncredit) ){
                    money=money+parseFloat(data.oncredit)
                }
            }
            $("#total_money").text(parseInt(money).toString())
        },
        check_select:function(){
            var d = a.getcheckdata();
            if(d.length<=0){
                js.msg('msg','请先用复选框选中行');
                return;
            }
            return d;
        }
	};	
	
	//表格参数设定
	var bootparams = {
		fanye:true,modenum:modenum,modename:modename,statuschange:false,tablename:jm.base64decode('ZmluaW5mb20:'),
		url:c.storeurl(),storeafteraction:'storeaftershow',storebeforeaction:'storebeforeshow',
		params:{atype:atype},
        checked:true,
        pageSize:100,
		columns:[{text:"申请人",dataIndex:"base_name",sortable:true},{text:"申请人部门",dataIndex:"base_deptname",sortable:true},{text:"单号",dataIndex:"sericnum"},{text:"编号",dataIndex:"code",sortable:true},{text:"还款金额",dataIndex:"returnmoney"},{text:"收到发票",dataIndex:"reincoice"},{text:"抵扣系数",dataIndex:"perinvoice"},{text:"待收发票",dataIndex:"dsinvoice",sortable:true},{text:"说明",dataIndex:"explain"},{text:"录入日期",dataIndex:"applydt",sortable:true},{text:"付款方式",dataIndex:"paytype",sortable:true},{text:"供应商",dataIndex:"supplier",sortable:true},{text:"购买日期",dataIndex:"paydt",sortable:true},{text:"原材料类型",dataIndex:"goodstype",sortable:true},{text:"总货款",dataIndex:"money",sortable:true},{text:"应收发票金额",dataIndex:"invoice",sortable:true},{text:"状态",dataIndex:"statustext"},{
			text:'',dataIndex:'caozuo',callback:'opegs{rand}'
		}],
		itemdblclick:function(){
			c.view();
		},
		load:function(d){
			c.loaddata(d);
		}
	};
	c.initcolumns(false);
	opegs{rand}=function(){
		c.reload();
	}
	
//[自定义区域start]

    c.setcolumns('explain',{
        renderer:function(v,d,i){
            return ''+v+'&nbsp;<a href="javascript:;" onclick="getoptlog{rand}('+i+')">查看</a>';
        }
    });

c.setcolumns('oncredit',{
        renderer:function(v,d,i){
            return ''+v+'&nbsp;<a href="javascript:;" onclick="viespere{rand}('+i+')">查看</a>';
        }
    });
    c.setcolumns('supplier',{
        renderer:function(v,d,i){
            return ''+v+'&nbsp;<a href="javascript:;" onclick="viespere{rand}('+i+')">查看</a>';
        }
    });
    getoptlog{rand}=function(id){
        var d 	= a.getData(id);
        url="index.php?a=getlog&d=my&m=finance&id="+d.id
        js.winiframe('详细记录',url);

    }
    viespere{rand}=function(id){
        var d 	= a.getData(id);
        url="index.php?a=getoncreditList&d=my&m=supplier&supplier="+d.supplier
        js.winiframe('供应商欠款金额',url);
        //js.open(url, 800,500);
    }

    function isNumber(val){
        var regPos = /^\d+(\.\d+)?$/; //非负浮点数
        var regNeg = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/; //负浮点数
        if(regPos.test(val) || regNeg.test(val)){
            return true;
        }else{
            return false;
        }

    }

//[自定义区域end]

	js.initbtn(c);
	var a = $('#viewfinycl_{rand}').bootstable(bootparams);
	c.init();
	var ddata = [{name:'高级搜索',lx:0}];
	if(admintype==1)ddata.push({name:'自定义列显示',lx:2});
	ddata.push({name:'打印',lx:1});
	$('#downbtn_{rand}').rockmenu({
		width:120,top:35,donghua:false,
		data:ddata,
		itemsclick:function(d, i){
			if(d.lx==0)c.searchhigh();
			if(d.lx==1)c.printlist();
			if(d.lx==2)c.setfieldslist();
		}
	});
	
	
});
</script>
<!--SCRIPTend-->
<!--HTMLstart-->
<div>
	<table width="100%">
	<tr>

		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" disabled type="button"><i class="icon-plus"></i> 新增</button></td>
		<td>
			<input class="form-control" style="width:160px" id="key_{rand}" placeholder="关键字/申请人/单号">
		</td>
		<td style="padding-left:10px"><select class="form-control" style="width:120px" id="selstatus_{rand}"><option value="">-全部状态-</option><option style="color:blue" value="0">待处理</option><option style="color:green" value="1">已审核</option><option style="color:red" value="2">不同意</option><option style="color:#888888" value="5">已作废</option><option style="color:#17B2B7" value="23">退回</option></select></td>
		<td style="padding-left:10px">
			<div style="width:85px" class="btn-group">
			<button class="btn btn-default" click="searchbtn" type="button">搜索</button><button class="btn btn-default" id="downbtn_{rand}" type="button" style="padding-left:8px;padding-right:8px"><i class="icon-angle-down"></i></button> 
			</div>
		</td>
        <td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button class="btn btn-primary" click="totalmoney,0" type="button">统计总金额</button></td>
        <td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button class="btn btn-primary" click="tototal_own,0" type="button">统计欠款</button></td>
        <td style="padding-right:10px;" id="tdleft_{rand}" nowrap>总金额:<font id="total_money"></font></td>
		<td  width="90%" style="padding-left:10px"><div id="changatype{rand}" class="btn-group"></div></td>
		<td align="right" id="tdright_{rand}" nowrap>
			<span style="display:none" id="daoruspan_{rand}"><button class="btn btn-default" click="daoru,1" type="button">导入</button>&nbsp;&nbsp;&nbsp;</span><button class="btn btn-default" click="daochu" type="button">导出 <i class="icon-angle-down"></i></button> 
		</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="viewfinycl_{rand}"></div>
<!--HTMLend-->