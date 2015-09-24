<?php

class AjaxController extends \Rainbow\Controllers\ControllerBase
{
	public function initialize()
	{
		$this->response->setStatusCode(200);
		$this->response->setContentType('application/json');
	}

    public function indexAction()
    {
    	ob_start();

        $data = [
        	'status' => 'ok',
        	'action' => 'index',
        	'view' => 'calc/index',
        	'form' => 'input-data',
        	'data' => []
    	];

    	// add Input fields
    	if(isset($_POST['input-data'])) {
			$data['data']['input'] = $this->generateInput($_POST['input-data']);
		}
		else {
			$data['data']['input'] = $this->generateInput(null);
			$data['data']['input'][0]['value'] = 100.0;
			$data['data']['input'][1]['value'] = 105.0;
			$data['data']['input'][2]['value'] = 10000.0;
			$data['data']['input'][3]['value'] = 11000.0;
			$data['data']['input'][4]['value'] = 12000.0;
			$data['data']['input'][5]['value'] = 0.0;
			$data['data']['input'][6]['value'] = 0.0;
		}

		// calculate and add Output
		if(isset($_POST['input-data']))
			$data['data']['output'] = $this->calculate($_POST['input-data']);

		$buf = ob_get_clean();
		if($buf) {
			$data['status'] = 'error';
			$data['error'] = $buf;
		}

		echo json_encode($data);
    }

    function generateInput($data)
    {
    	if(!$data)
    		$data = [];

    	$pd = $data['i-pd'] * 1.0;
    	$fd = $data['i-fd'] * 1.0;
    	$prpz = $data['i-prpz'] * 1.0;
    	$prfz = $data['i-prfz'] * 1.0;
    	$frfz = $data['i-frfz'] * 1.0;
    	$prpr = $data['i-prpr'] * 1.0;
    	$ppr = $data['i-ppr'] * 1.0;

    	$inputData = [
			[
				'info' => 'срок по плану',
				'caption' => 'ПД',
				'name' => 'i-pd',
				'type' => 'number',
				'value' => $pd,
			],
			[
				'info' => 'срок по факту',
				'caption' => 'ФД',
				'name' => 'i-fd',
				'type' => 'number',
				'value' => $fd,
			],
			[
				'info' => 'бюджет',
				'caption' => 'ПРПЗ',
				'name' => 'i-prpz',
				'type' => 'number',
				'value' => $prpz,
			],
			[
				'info' => 'план потрачено',
				'caption' => 'ПРФЗ',
				'name' => 'i-prfz',
				'type' => 'number',
				'value' => $prfz,
			],
			[
				'info' => 'факт потрачено',
				'caption' => 'ФРФЗ',
				'name' => 'i-frfz',
				'type' => 'number',
				'value' => $frfz,
			],
			[
				'caption' => 'ПРПР',
				'name' => 'i-prpr',
				'type' => 'number',
				'value' => $prpr,
			],
			[
				'info' => 'план по прибыли',
				'caption' => 'ППР',
				'name' => 'i-ppr',
				'type' => 'number',
				'value' => $ppr,
			],
		];

		return $inputData;
    }

    function calculate($data) {
    	$pd = $data['i-pd'] * 1.0;
    	$fd = $data['i-fd'] * 1.0;
    	$prpz = $data['i-prpz'] * 1.0;
    	$prfz = $data['i-prfz'] * 1.0;
    	$frfz = $data['i-frfz'] * 1.0;
    	$prpr = $data['i-prpr'] * 1.0;
    	$ppr = $data['i-ppr'] * 1.0;

    	$kd = ($fd<$pd ? 1.0 : $pd/$fd);
    	$ks = ($prfz/$prpz) * $kd;
    	$kpr = (!$prpr || !$ppr ? 1.0 : $prpr / $ppr);
    	$kr = 1+(1-$frfz/$prfz)*$kpr;
    	$eff = $ks*$kr;

    	$outputData = [
    		[
    			'caption' => 'Кд',
    			'name' => 'o-kd',
    			'value' => $kd,
    		],
    		[
    			'caption' => 'Кс',
    			'name' => 'o-ks',
    			'value' => $ks,
    		],
    		[
    			'caption' => 'Кпр',
    			'name' => 'o-kpr',
    			'value' => $kpr,
    		],
    		[
    			'caption' => 'Кр',
    			'name' => 'o-kr',
    			'value' => $kr,
    		],
    		[
    			'caption' => 'Э',
    			'name' => 'o-eff',
    			'value' => $eff,
    		],
    	];

    	return $outputData;
    }
}
