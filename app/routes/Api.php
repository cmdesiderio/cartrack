<?php
namespace Cartrack\Routes;

class Api
{
	public function uri(): array
	{
		// uri[0] - null
		// uri[1] - Cartrack :base name
		// uri[2] - api : api
		// uri[3] - v1 : api version
		// uri[4] - persons : api name
		// uri[5] - int : id

		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode( '/', $uri);

		$id = null;
		if (! empty($uri[5])) {
			$id = (int) $uri[5];
		}

		return array(
			'base'    => $uri[1],
			'type'    => $uri[2],
			'version' => $uri[3],
			'name'    => $uri[4],
			'id'      => $id
		);
	}
}