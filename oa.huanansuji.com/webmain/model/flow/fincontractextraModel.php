<?php

class flow_fincontractextraClassModel extends flowModel
{
	public function initModel(){

	}


    protected function flowoptmenu($ors, $arrs)
    {

        //删除
        if($ors['num']=='delete'){
            m('cash')->delete("heid=".$this->id);
            m('fininfom')->delete("id=".$this->id);

        }
    }
}