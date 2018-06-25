<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/9 0009
 * Time: 下午 4:09
 */

class flow_finjhkClassModel extends flowModel{
    public function initModel(){



    }
    protected function flowoptmenu($ors, $arrs)
    {

        if($ors['num']=='delete'){
            m('fininfom')->delete('id='.$this->id);
            m('cash')->delete('jhkid='.$this->id);
        }
        if($ors['num']=='returnpart'){
            $rs=m('fininfom')->getone("id=$this->id");
            $returnmoney=$arrs['fields_returnmoney'];
            $payway=$arrs['fields_paytype2'];

            $code=$rs['code'];
            $supplier=$rs['supplier'];
            $explain=$rs['explain'];
            $now =date('Y-m-d');
            $arr=[];
            $arr['nopay']=$rs['nopay']-$returnmoney;
            $arr['readypay']=$rs['readypay']+$returnmoney;
            if($arr['nopay']<0){
                $arr['nopay']=0;
                $arr['extrafee']=$arr['readypay']-$rs['money'];
            }


            if($explain){
                $tr='<br>';
            }
            else{
                $tr='';
            }
            $explain=$explain.$tr.$now."录入还款:".floatval($rs['returnmoney']) ;
            $arr['explain']=$explain;
            $arr['returnmoney']=null;
            $arr['tempdate']=null;
            $arr['paytype2']=null;
            m('fininfom')->update($arr,"`id` in($this->id)");
            //借还款信息
            $dataarr=array();
            $explain="";
            $explain="借还款还款: 编号".$code;
            $dataarr['money']=$arrs['fields_returnmoney'];
            $dataarr['code']=$rs['code'];
            $dataarr['path']=$arrs['fields_paytype2'];

            if($rs['type']=='公司借贷'){
                $dataarr['way']='出';
            }
            else{
                $dataarr['way']='进';
            }
           $dataarr['happenddate']=$arrs['fields_tempdate'];
            $dataarr['path']=$arrs['fields_paytype2'];
            $dataarr['optdate']=$now;
            $dataarr['explain']=$explain;
            $dataarr['jhkid']=$this->id;

            m('cash')->insert($dataarr);

        }

    }
    public function flowrsreplace($rs, $lx=0)
    {
        if($rs['readypay']==$rs['money']){
            $rs['readypay']='<font color="green">已结清</font>';
        }
        if($rs['nopay']=='0.00'|| $rs['readypay']==''){
            $rs['nopay']='<font color="green">已结清</font>';
        }


        return $rs;
    }


}