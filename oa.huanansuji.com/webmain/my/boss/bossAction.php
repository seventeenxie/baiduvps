<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27
 * Time: 12:43
 */
class bossClassAction extends mainAction
{
    public function bossAction()
    {

    }

    public function bossDetailAction()
    {
        $return = array();
        $data = array();
        $dataDdetail = array();
        $text = '';
        $subtext = "";
        $nowdate=date("Y-m-d");
        $stardate = $this->get("startdate");
        $enddate = $this->get("enddate");
        if ($this->get("type") == 'bossknow') {
            $text = "老板财务";
            $sql = 'SELECT path,way,SUM(money) as money from xinhu_cash group by path,way ORDER BY path,way DESC ';
            $rs = $this->db->getall($sql);
            $temp_in = 0;
            $i = 1;
            $total=0;
            foreach ($rs as $key => $item) {
                if ($i % 2 > 0) {
                    $temp_in = $item['money'];
                } else {
                    $dataDdetail['name'] = $item['path'];
                    $dataDdetail['value'] = bcsub($temp_in, $item['money'], 2);
                    $total=bcadd($total,$dataDdetail['value'],2);
                    array_push($data, $dataDdetail);
                }
                $i = $i + 1;
            }
            $text="剩余总金额：".$total;




            $sql = "select sum(money) as money from [Q]fininfom where type='公司借贷'";
            $row = $this->db->getall($sql);
            $finance = floatval($row[0]['money']);
            $dataDdetail['name'] = '公司借贷';
            $dataDdetail['value'] = $finance;
            $subtext="公司借贷:".$dataDdetail['value'];
            array_push($data, $dataDdetail);

            $sql = "select sum(money) as money from [Q]fininfom where type='个人借款'";
            $row = $this->db->getall($sql);
            $finance = floatval($row[0]['money']);
            $dataDdetail['name'] = '个人借款';
            $dataDdetail['value'] = $finance;
            $subtext=$subtext."--个人借贷:".$dataDdetail['value'];
            array_push($data, $dataDdetail);
            $return['data'] = $data;
            $return['datatable'] = $data;
            $return['text'] = $text;
            $return['subtext'] = $subtext;
            return json_encode($return);
        }
//供应商欠款
        if ($this->get("type") == 'financedetail') {
            $sql = " select supplier as name,SUM(oncredit) as value from xinhu_fininfom  GROUP BY supplier order by value desc";
            return $this->built_data($sql, $total = true);
        }
        if ($this->get("type") == 'supplierrecent') {
            $sql = " select supplier as name,sum(money) as value from xinhu_fininfom WHERE type='原材料' and `paydt`>='$stardate' and `paydt`<='$enddate' GROUP BY supplier ORDER by value DESC";
            return $this->built_data($sql, $total = true);
        }

        if ($this->get("type") == 'contract') {
            $sql = "select sum(money) as value,optname as name from xinhu_custract where `signdt`>='$stardate' and `signdt`<='$enddate' GROUP BY optname";
            return $this->built_data($sql, $total = true);
        }
//待收金额
        if ($this->get("type") == 'duein') {
            $sql = "select  sum(moneys) as value,optname as name from xinhu_custract GROUP BY optname";
            return $this->built_data($sql, $total = true,'待收');
        }

        if ($this->get("type") == 'pastduein') {

            $sql = "select  sum(moneys) as value,optname as name from xinhu_custract where  `enddt`<'  $nowdate' GROUP BY optname";
            return $this->built_data($sql, $total = true,'过期待收');
        }

        if ($this->get("type") == 'pastdueinnotfinish') {

            $sql = "select sum(moneys) as value,optname as name from xinhu_custract where enddt<'$nowdate' and moneys>0 and statetext not in(1) GROUP BY optname";
            return $this->built_data($sql, $total = true,'过期待收未完成');
        }

        if ($this->get("type") == 'pastfinish') {

            $sql = "select sum(moneys) as value,optname as name from xinhu_custract where enddt<'$nowdate'  and moneys>0 and statetext=1 GROUP BY optname";
            return $this->built_data($sql, $total = true,'完成未收');
        }
        if ($this->get("type") == 'finceout') {
            $sql = "select sum(money) as value,type as name from xinhu_fininfom where `paydt`>='$stardate' and `paydt`<='$enddate' and (`path`='支出' or `path` is NULL) and `type` not in('个人借款','公司借贷') GROUP BY type ORDER BY sum(money)  desc";
            return $this->built_data($sql, $total = true, '公司支出', '', '公司支出');
        }
        if ($this->get("type") == 'finceoutdetail') {
            $sql=<<<c
           SELECT
	sum(VALUE) AS money
FROM
	(
		(
			SELECT
				SUM(money) AS
			VALUE

			FROM
				xinhu_fininfom AS `b`
			WHERE
				(
					(
						type = '合同收支'
						AND path = '收到'
					)
				)
			AND `paydt` >= '$stardate'
			AND `paydt` <= '$enddate'
			GROUP BY
				`type`
		)
		UNION
			(
				SELECT
					SUM(money) AS
				VALUE

				FROM
					xinhu_custract
				WHERE
					signdt >= '$stardate'
				AND signdt <= '$enddate'
			)
	) AS c
c;

            $row=$this->db->getall($sql);
            $sql = "SELECT * FROM ( ( SELECT sum(money) AS value, name AS name FROM xinhu_fininfos WHERE `sdt`>='$stardate' and `sdt`<='$enddate' GROUP BY NAME ) UNION ( SELECT SUM(money) AS value, type AS name FROM xinhu_fininfom AS `b` WHERE (( type = '合同收支' AND path = '支出' ) OR type in ('原材料','薪酬')) and `paydt`>='$stardate' and `paydt`<='$enddate' group by `type`) ) c ORDER BY value DESC";
            return $this->built_data($sql, $total = true, '支出', '公司收入'.$row[0]['money'], '公司支出明细',$row[0]['money'],"销售");
        }
        if ($this->get("type") == 'financein') {
            $sql=<<<c
SELECT
	*
FROM
	(
		(
			SELECT
				sum(money) AS
			value
				,
				optname AS name
			FROM
				xinhu_custract
			WHERE
				`signdt` >= '$stardate'
			AND `signdt` <= '$enddate'
			GROUP BY
				optname
		)
		UNION
			(
				SELECT
					SUM(money) AS
				value
					,
					type AS name
				FROM
					xinhu_fininfom AS `b`
				WHERE
					(
						(
							type = '合同收支'
							AND path = '收到'
						)
					
					)
				AND `paydt` >= '$stardate'
				AND `paydt` <= '$enddate'
				GROUP BY
					`type`
			)
	) c
ORDER BY

VALUE
	DESC
c;

            return $this->built_data($sql, $total = true, '', '', '公司收入');
        }

        if ($this->get("type") == 'allds') {
            $sql=<<<c
SELECT
	moneys as value,
	concat(
		'合同内容:',
		content,
		'<br>合同金额',
		money,
		'<br>拥有者:',
		optname,
		'<br>'
		'签约时间',
    signdt,
'<br>',
'交货时间:',
enddt,
'<br>',
'待收金额:',
moneys
	) as name
FROM
	xinhu_custract where moneys>0  order by optname desc

c;

            return $this->built_data($sql, $total = true, '合同待收明细', '', '合同待收明细');


        }


        if ($this->get("type") == 'allpastds') {
            $sql=<<<c
SELECT
	moneys as value,
	concat(
		'合同内容:',
		content,
		'<br>合同金额',
		money,
		'<br>拥有者:',
		optname,
		'<br>'
		'签约时间',
    signdt,
'<br>',
'交货时间:',
enddt,
'<br>',
'待收金额:',
moneys
	) as name
FROM
	xinhu_custract where moneys>0 and enddt<'$nowdate' order by optname desc

c;


            return $this->built_data($sql, $total = true, '过期待收明细', '', '过期待收明细');


        }

        if ($this->get("type") == 'allpastdsnotfinish') {
            $sql=<<<c
SELECT
	moneys as value,
	concat(
		'合同内容:',
		content,
		'<br>合同金额',
		money,
		'<br>拥有者:',
		optname,
		'<br>'
		'签约时间',
    signdt,
'<br>',
'交货时间:',
enddt,
'<br>',
'待收金额:',
moneys
	) as name
FROM
	xinhu_custract where moneys>0 and enddt<'$nowdate' and statetext not in(1) order by optname desc

c;


            return $this->built_data($sql, $total = true, '过期待收未完成明细', '', '过期待收未完成明细');


        }


        if ($this->get("type") == 'pastfinishdetail') {
            $sql=<<<c
SELECT
	moneys as value,
	concat(
		'合同内容:',
		content,
		'<br>合同金额',
		money,
		'<br>拥有者:',
		optname,
		'<br>'
		'签约时间',
    signdt,
'<br>',
'交货时间:',
enddt,
'<br>',
'待收金额:',
moneys
	) as name
FROM
	xinhu_custract where moneys>0 and enddt<'$nowdate' and statetext=1 order by optname desc

c;


            return $this->built_data($sql, $total = true, '完成未收明细', '', '完成未收明细');


        }





    }









    public function built_data($sql, $total = true, $text = '', $sub_text = '', $title = '',$othertotal=0,$othertotaltext='')
    {
        $rs = $this->db->getall($sql);
        $dataDdetail = array();
        $data = array();
        $data_table=array();
        $toral = 0;

        foreach ($rs as $key => $item ){
            if (floatval($item['value']) > 0) {
                $total = bcadd($total, floatval($item['value']), 2);
            }
        }
        foreach ($rs as $key => $item) {
            if (floatval($item['value']) > 0) {
                $dataDdetail['name'] = $item['name'];
                $dataDdetail['value'] = $item['value'];
                array_push($data, $dataDdetail);
                if($othertotal){

                    $dataDdetail['value'] = $item['value']."-$text"."占比". 100*round($item['value']/$total,4).'%'."--$othertotaltext"."比". 100*round($item['value']/$othertotal,4).'%';
                }
                else{
                    $dataDdetail['value'] = $item['value']."-$text"."占比". 100*round($item['value']/$total,4).'%';
                }
                array_push($data_table, $dataDdetail);

            }
        }
        $return['datatable']=$data_table;
        $return['data'] = $data;
        $return['text'] = $text;
        $return['total'] = $total;
        $return['subtext'] = $sub_text;
        $return['title'] = $title;
        return json_encode($return);
    }

}




