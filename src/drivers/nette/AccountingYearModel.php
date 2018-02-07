<?php

namespace ViPErCZ\DocumentSeries\Models\Drivers\Nette;

use DateTime;
use Nette\Database\Context;
use ViPErCZ\DocumentSeries\Entity\AccountingYear;
use ViPErCZ\DocumentSeries\Models\IAccountingYearModel;

/**
 * Class AccountingYearModel
 * @package ViPErCZ\DocumentSeries\Models\Drivers\Nette
 */
final class AccountingYearModel implements IAccountingYearModel {

	/** @var Context */
	private $connection;

	/**
	 * AccountingYearModel constructor.
	 * @param Context $connection
	 */
	public function __construct(Context $connection) {
		$this->connection = $connection;
	}

	public function getAccountingYear($id): ?AccountingYear {
		// TODO: Implement getAccountingYear() method.
	}

	public function getActualYear(): ?AccountingYear {
		// TODO: Implement getActualYear() method.
	}

	public function getYear(DateTime $dateTime): ?AccountingYear {
		// TODO: Implement getYear() method.
	}

}