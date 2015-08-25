<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use SoapBox\Formatter\Formatter;

class Controller extends BaseController {

	/**
	 * @param  array $content
	 * @param  integer $code
	 * @param  string $format Доступны те же форматы, что и в 
	 * 						  SoapBox\Formatter\Formatter: json, csv, xml, 
	 * 						  array, yaml. 
	 * @return Illuminate\Http\Response
	 */
	public static function response(array $content = [], $code = 200, $format = 'json') {
		$formatter = Formatter::make($content, Formatter::ARR);

		switch ($format) {
			case 'xml':
				$response_data = $formatter->toXml();
				$content_type = 'application/xml';
				break;
			case 'csv':
				$response_data = $formatter->toCsv();
				$content_type = 'text/csv';
				break;

			case 'json':
			default:
				$response_data = $formatter->toJson();
				$content_type = 'application/json';
				break;
		}

		$headers = [
			'Content-Type' => $content_type
		];
		
		return response()->make($response_data,$code,$headers);
	}

}
