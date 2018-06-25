<?php

class flow_finyclClassModel extends flowModel
{
    public function initModel()
    {


    }

    protected function flowoptmenu($ors, $arrs)
    {
        $rs = m('fininfom')->getone("id=$this->id");
        $paytype = $rs['paytype'];
        $code = $rs['code'];
        $supplier = $rs['supplier'];
        $now = date('Y-m-d');

        ///收到发票
        if ($ors['num'] == 'chdsinvoice') {
            if ($arrs['fields_reincoice'] == "" || $arrs['fields_reincoice'] == 0) {
                $this->echomsg("请输入正确的发票金额");
            }
            if ($arrs['fields_tempdate'] == "" || $arrs['fields_tempdate'] == null) {
                $this->echomsg("请输入发生日期");
            }
            $arr = [];


            //处理发票
            $preinvoice= floatval($rs['dsinvoice'] ) -floatval($rs['reincoice']);
            if($preinvoice>=0){
                $arr['dsinvoice']=$preinvoice;
            }
            if($preinvoice<0){
                $arr['preinvoice']=floatval($rs['preinvoice'])+ abs($preinvoice);
                $arr['invoice']=floatval($rs['invoice']) +floatval($rs['reincoice']);
            }
            $arr['areadinvoice']=floatval($rs['areadinvoice']) +floatval($rs['reincoice']);


            $explain = $arrs['fields_tempdate'] . "收到发票:" . intval($rs['reincoice']);
            $arr['reincoice'] = null;
            $arr['tempdate'] = null;
            $arr['explain'] = $explain;
            m('fininfom')->update($arr, "`id`=$this->id");
            $datalog = array();
            $datalog['type'] = '发票';
            $datalog['code'] = $rs['code'];
            $datalog['money'] = $arrs['fields_reincoice'];
            $datalog['optdate'] = $now;
            $datalog['happenddate'] = $arrs['fields_tempdate'];
            $datalog['operate'] = "收到发票";
            $datalog['mid'] = $this->id;
            $datalog['explain'] = $arrs['sm'];

            m('fininfomlog')->insert($datalog);
        }



        if ($ors['num'] == 'changeoncredit') {

           if ($arrs['fields_paytype2'] == "" || $arrs['fields_paytype2'] == null) {
                $this->echomsg("请录入支付方式");
            }
            if ($arrs['fields_returnmoney'] == "" || $arrs['fields_returnmoney'] == 0) {
                $this->echomsg("请输入正确的还款金额");
              }
                if ($arrs['fields_tempdate'] == "" || $arrs['fields_tempdate'] == null) {
                    $this->echomsg("请输入发生日期");
                }
                $arr = [];
                $arr['oncredit'] = $rs['oncredit'] - $rs['returnmoney'];
                $explain = $now . "录入还款:" . floatval($rs['returnmoney']) . '<br>' . $arrs['fields_paytype2'] . "支付" . $arrs['sm'];
                $arr['explain'] = $explain;
                $arr['returnmoney'] = null;
                $arr['paytype2'] = null;
                $arr['tempdate'] = null;
                if ($arrs['fields_paytype2'] == '公账卡') {

                    if($rs['preinvoice']>0){
                        $temp_invoice=floatval($rs['preinvoice'])-floatval($rs['returnmoney']);
                        if ($temp_invoice>=0){
                            $arr['preinvoice']=$temp_invoice;
                        }
                        else{
                            $arr['preinvoice']=0;
                            $arr['dsinvoice']=floatval($rs['dsinvoice'])+abs($temp_invoice) ;
                            $arr['invoice'] =floatval($rs['invoice'])  +abs($temp_invoice) ;
                        }
                    }
                    else{
                        $arr['dsinvoice'] = floatval($rs['dsinvoice']) + floatval($rs['returnmoney']);

                        $arr['invoice'] =floatval($rs['invoice'])  + floatval($rs['returnmoney']);

                    }
                }
                m('fininfom')->update($arr, "`id` in($this->id)");
                //更新财务还款
                $dataarr = array();
                $explain = "";
                $explain = "原材料还款: 原材料编号" . $code . "供应商 " . $supplier . $arrs['sm'];
                $dataarr['money'] = $arrs['fields_returnmoney'];
                $dataarr['path'] = $arrs['fields_paytype2'];
                $dataarr['way'] = '出';
                $dataarr['optdate'] = $now;
                $dataarr['happenddate'] = $arrs['fields_tempdate'];
                $dataarr['explain'] = $explain;
                $dataarr['finid'] = $this->id;
            $dataarr['code'] = $code;
                m('cash')->insert($dataarr);


                //更新日志
                $datalog = array();
                $datalog['type'] = '还款';
                $datalog['code'] = $rs['code'];
                $datalog['paytype'] = $arrs['fields_paytype2'];
                $datalog['money'] = $rs['returnmoney'];
                $datalog['optdate'] = $now;
                $datalog['happenddate'] = $arrs['fields_tempdate'];
                $datalog['operate'] = "还款";
                $datalog['mid'] = $this->id;
                $datalog['explain'] = $arrs['sm'];
                m('fininfomlog')->insert($datalog);

            }

            if ($ors['num'] == 'payoncredit') {
                if ($arrs['fields_oncredit'] == "" || $arrs['fields_oncredit'] == 0) {
                    $this->echomsg("请输入正确的还款金额");
                }
                if ($arrs['fields_paytype2'] == "" || $arrs['fields_paytype2'] == null) {
                    $this->echomsg("请录入支付方式");
                }
                if ($arrs['fields_tempdate'] == "" || $arrs['fields_tempdate'] == null) {
                    $this->echomsg("请输入发生日期");
                }

                $rs = m('fininfom')->getone("id=$this->id");
                $arr = [];
                $arr['oncredit'] = intval($rs['oncredit'] - $arrs['fields_oncredit']);
                $explain = '';
                if ($arr['oncredit'] == 0) {
                    $explain = $now . "已还清货款" . '<br>通过' . $arrs['fields_paytype2'] . '还款<br>' . $arrs['sm'];
                } else {
                    $explain = $now . "还款" . $arrs['fields_oncredit'] . '<br>通过' . $arrs['fields_paytype2'] . '还款<br>' . $arrs['sm'];
                }
                if ($arrs['fields_paytype2'] == '公账卡') {
                    $arr['invoice'] = floatval($rs['invoice']) + floatval($arrs['fields_oncredit']);
                    $arr['dsinvoice'] = floatval($rs['dsinvoice']) + floatval($arrs['fields_oncredit']);
                }
                $arr['explain'] = $explain;
                $arr['paytype2'] = null;
                $arr['tempdate'] = null;

                m('fininfom')->update($arr, "`id` in($this->id)");

                //更新财务还款
                $dataarr = array();
                $explain = "";
                $explain = "原材料还款: 原材料编号" . $code . "供应商 " . $supplier . $arrs['sm'];
                $dataarr['money'] = $arrs['fields_oncredit'];
                $dataarr['path'] = $arrs['fields_paytype2'];
                $dataarr['way'] = '出';
                $dataarr['optdate'] = $now;
                $dataarr['code'] = $code;
                $dataarr['happenddate'] = $arrs['fields_tempdate'];
                $dataarr['explain'] = $explain;
                $dataarr['finid'] = $this->id;
                m('cash')->insert($dataarr);


                //更新日志
                $datalog = array();
                $datalog['type'] = '还款';
                $datalog['code'] = $rs['code'];
                $datalog['paytype'] = $arrs['fields_paytype2'];
                $datalog['money'] = $arrs['fields_oncredit'];
                $datalog['optdate'] = $now;
                $datalog['happenddate'] = $arrs['fields_tempdate'];
                $datalog['operate'] = "还款";
                $datalog['mid'] = $this->id;
                $datalog['explain'] = $arrs['sm'];
                m('fininfomlog')->insert($datalog);

            }
            if ($ors['num'] == 'invoiceok') {
                if ($arrs['fields_tempdate'] == "" || $arrs['fields_tempdate'] == null) {
                    $this->echomsg("请输入发生日期");
                }
                $arr = [];
                $arr['dsinvoice']=0;
                $arr['preinvoice']=0;
                $arr['areadinvoice']=$rs['invoice'];
                $arr['tempdate']=null;
                m('fininfom')->update($arr, "`id` in($this->id)");

                $datalog = array();
                $datalog['type'] = '发票';
                $datalog['code'] = $rs['code'];
                $datalog['optdate'] = $now;
                $datalog['happenddate'] = $arrs['fields_tempdate'];
                $datalog['operate'] = "收完发票";
                $datalog['mid'] = $this->id;
                $datalog['explain'] = $arrs['sm'];

                m('fininfomlog')->insert($datalog);

            }
            //删除
            if ($ors['num'] == 'delete') {
                m('cash')->delete("finid=" . $this->id);
                m('fininfom')->delete("id=" . $this->id);
                m('fininfomlog')->delete("mid=" . $this->id);

            }


    }

    public function flowrsreplace($rs)
    {
        if ($rs['dsinvoice'] == '0.00'||$rs['dsinvoice'] == '') {
            $rs['dsinvoice'] = '<font color="green">0</font>';
        }
        if ($rs['invoice'] == '0.00' || $rs['invoice'] =='') {
            $rs['invoice'] = '<font color="green">0</font>';
        }
        if ($rs['preinvoice'] == '0.00' || $rs['preinvoice'] =='') {
            $rs['preinvoice'] = '<font color="green">0</font>';
        }
        if ($rs['areadinvoice'] == '0.00' || $rs['areadinvoice'] =='') {
            $rs['areadinvoice'] = '<font color="green">0</font>';
        }


        if ($rs['oncredit'] == '0.00') {
            $rs['oncredit'] = '<font color="green">已结清货款</font>';
        }
        return $rs;
    }

}