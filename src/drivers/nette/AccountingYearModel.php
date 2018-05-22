<?php

namespace ViPErCZ\DocumentSeries\Models\Drivers\Nette;

use DateTime;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use ViPErCZ\DocumentSeries\DriverException;
use ViPErCZ\DocumentSeries\Entity\AccountingYear;
use ViPErCZ\DocumentSeries\Models\IAccountingYearModel;

/**
 * Class AccountingYearModel
 * @package ViPErCZ\DocumentSeries\Models\Drivers\Nette
 */
final class AccountingYearModel implements IAccountingYearModel {

	/** Table name */
	private const TABLE = 'accountingYears';

	/** @var Context */
	private $connection;

	/**
	 * AccountingYearModel constructor.
	 * @param Context $connection
	 */
	public function __construct(Context $connection) {
		$this->connection = $connection;
	}

	/**
	 * @param $id
	 * @return null|AccountingYear
	 * @throws DriverException
	 */
	public function getAccountingYear($id): ?AccountingYear {
		try {
			$year = $this->connection->table(self::TABLE)->get($id);
			if ($year) {
				return $this->createEntity($year);
			} else {
				return null;
			}
		} catch (\PDOException $exception) {
			throw new DriverException($exception->getMessage());
		}
	}

	/**
	 * @param ActiveRow $year
	 * @return AccountingYear
	 */
	private function createEntity(ActiveRow $year): AccountingYear {
		$entity = new AccountingYear();
		$entity->setId($year->id);
		$entity->setActive($year->active);
		$entity->setDateInserted($year->dateInserted);
		$entity->setDateUpdated($year->dateUpdated);
		$entity->setYear($year->year);

		return $entity;
	}

	/**
	 * @return null|AccountingYear
	 * @throws DriverException
	 */
	public function getActualYear(): ?AccountingYear {
		return $this->getYear(new DateTime());
	}

	/**
	 * @param DateTime $dateTime
	 * @return null|AccountingYear
	 * @throws DriverException
	 */
	public function getYear(DateTime $dateTime): ?AccountingYear {
		try {
			$year = $this->connection->table(self::TABLE)->where('year', $dateTime->format('Y'))->limit(1)->fetch();
			if ($year) {
				return $this->createEntity($year);
			} else {
				return null;
			}
		} catch (\PDOException $exception) {
			throw new DriverException($exception->getMessage());
		}
	}

	/**
	 * @param AccountingYear $accountingYear
	 * @throws DriverException
	 */
	public function insertAccountingYear(AccountingYear $accountingYear): void {
		try {
			if ($this->hasYear($accountingYear->getYear())) {
				throw new DriverException('Duplicate year ' . $accountingYear->getYear() . ' !');
			}

			$this->connection->table(self::TABLE)->insert([
				'year' => $accountingYear->getYear(),
				'dateInserted' => $accountingYear->getDateInserted(),
				'active' => $accountingYear->isActive()
			]);
		} catch (\PDOException $exception) {
			throw new DriverException($exception->getMessage());
		}
	}

	/**
	 * @param int $year
	 * @return bool
	 * @throws DriverException
	 */
	public function hasYear(int $year): bool {
		try {
			$count = $this->connection->table(self::TABLE)->where('year', $year)->count('*');

			return $count === 1;
		} catch (\PDOException $exception) {
			throw new DriverException($exception->getMessage());
		}
	}

}