<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Best\DestinationPoint;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Response;

class DestinationPointController extends Controller {

	/**
	 * Возвращает инормацию о точке
	 *
	 * @todo Ограничить отдаваемые поля
	 * @param  string $format Формат, в котором необходимо вернуть ответ
	 * @param  int $point_id Идентификатор искомой точки
	 * @return Illuminate\Http\Response
	 */
	public function getPoint($format, $point_id) {
		try {
			$point = DestinationPoint::where('id', $point_id)->where('is_published', 1)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			return static::response(['code' => 404, 'message' => "Destination Point with id=$point_id not found"], 404, $format);
		}

		return static::response($point->toArray(), 200, $format);
	}

	/**
	 * Возвращает массив точек в городе с заданным идентификатором
	 *
	 * @param  string $format Формат, в котором необходимо вернуть ответ
	 * @param  int $city_id Идентификатор искомого города
	 * @return Illuminate\Http\Response
	 */
	public function getPointsInCity($format, $city_id) {
		$points = DestinationPoint::where('city_id', $city_id)->where('is_published', 1)->get();

		return static::response($points->toArray(), 200, $format);
	}

	/**
	 * Возвращает список точек в городе в прямоугольнике с заданными координатами
	 * левой верхней и правой нижней точек.
	 * Обёртка для обработки частного случая от метода getPointsInRectangle()
	 *
	 * @param  Illuminate\Http\Request $request
	 * @param  string $format Формат, в котором необходимо вернуть ответ
	 * @param  int $city_id Идентификатор искомого города
	 * @return Illuminate\Http\Response
	 */
	public function getPointsInCityInRectangle(Request $request, $format, $city_id) {
		return $this->getPointsInRectangle($request, $format, $city_id);
	}

	/**
	 * Возвращает список точек в городе в прямоугольнике с заданными координатами
	 * левой верхней и правой нижней точек.
	 *
	 * @param  Illuminate\Http\Request $request
	 * @param  string $format Формат, в котором необходимо вернуть ответ
	 * @param  int $city_id (optional) Идентификатор искомого города
	 * @return Illuminate\Http\Response
	 */
	public function getPointsInRectangle(Request $request, $format, $city_id = null) {
		$input = $request->all();

		$latlong1 = static::validateLatLongParam($input['latlong1']);
		$latlong2 = static::validateLatLongParam($input['latlong2']);

		if ($latlong1 !== false && $latlong2 !== false) {
			list($lat1, $long1) = $latlong1;
			list($lat2, $long2) = $latlong2;

			$points_query = DestinationPoint::where('is_published', 1)
				->where('latitude', '>', $lat1)
				->where('latitude', '<', $lat2)
				->where('longitude', '>', $long1)
				->where('longitude', '<', $long2);

			$system_id = $request->get('system_id', false);
			if ($system_id !== false) {
				$points_query = $points_query->where('system_id', $system_id);
			}

			if (is_null($city_id)) {
				$city_id = $request->get('city_id', null);
			}
			if (!is_null($city_id)) {
				$points_query = $points_query->where('city_id', $city_id);
			}

			$points = $points_query->get();

			return static::response($points->toArray(), 200, $format);
		}

		return static::response(['code' => 400, 'message' => "Bad request"], 404, $format);
	}

	/**
	 * Выполняет валидацию координат точки
	 *
	 * @param  string $latlong Строка с координатами точки формата "xx.xxxxxxxx,xx.xxxxxxxx"
	 * @return false|array false - если переданный параметр не удовлетворяет условиям,
	 * 		   array - если параметр удовлетворяет условиям, то возвращается массив с долготой
	 * 		   и широтой точки
	 */
	protected static function validateLatLongParam($latlong = null) {
		if (isset($latlong)) {
			if (strpos($latlong, ',') === false) {
				return false;
			}

			list($lat, $long) = explode(",", $latlong, 2);
			$empty = (empty($lat) || empty($long));
			$valid_coordinates = (!static::isValidLatitude($lat) || !static::isValidLongitude($long));

			if ($empty || $valid_coordinates) {
				return false;
			} else {
				return [$lat, $long];
			}
		}

		return false;
	}

	/**
	 * Выполняет валидацию широты
	 *
	 * @param  string $latitude Широта
	 * @return boolean
	 */
	protected static function isValidLatitude($latitude) {
		if (preg_match("/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,8}$/", $latitude) && abs($latitude) <= 90) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Выполняет валидацию долготы
	 *
	 * @param  string $longitude Долгота
	 * @return boolean
	 */
	protected static function isValidLongitude($longitude) {
		if (preg_match("/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,8}$/",
			$longitude)  && abs($longitude) <= 180) {
			return true;
		} else {
			return false;
		}
	}
}
