<?php

namespace ViPErCZ\DocumentSeries\Models\Drivers;

use ViPErCZ\DocumentSeries\Models\Drivers\Nette\AccountingYearModel;
use ViPErCZ\DocumentSeries\Models\Drivers\Nette\SeriesModel;

/**
 * Class NetteDriverList
 * @package ViPErCZ\DocumentSeries\Models\Drivers
 */
class NetteDriverList implements IDriverList {

	/**
	 * @return array
	 */
	public static function getDrivers(): array {
		return [
			'accountingYear' => AccountingYearModel::class,
			'documentSeries' => SeriesModel::class
		];
	}
}