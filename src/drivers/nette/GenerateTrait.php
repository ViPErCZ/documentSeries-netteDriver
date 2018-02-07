<?php

namespace ViPErCZ\DocumentSeries\Models\Drivers\Nette;

use DateTime;
use Nette\Database\Context;
use ViPErCZ\DocumentSeries\DriverException;
use ViPErCZ\DocumentSeries\Entity\Serie;
use ViPErCZ\DocumentSeries\Models\Drivers\DefaultGenerateTrait;

/**
 * Trait GenerateTrait
 * @package ViPErCZ\DocumentSeries\Models\Drivers\Nette
 */
trait GenerateTrait {

	use DefaultGenerateTrait;

	/**
	 * @param Serie $serie
	 * @return string
	 * @throws DriverException
	 */
	public function generateNumber(Serie $serie): string {
		$selfTransaction = false;
		try {
			if ($this->connection && $this->connection instanceof Context) {
				if ($this->connection->getConnection()->getPdo()->inTransaction() === false) {
					$this->connection->beginTransaction();
					$selfTransaction = true;
				}
				$refreshSerie = $this->getSerie($serie->getId());
				$now = new DateTime();
				$this->prepareNumber($refreshSerie, $now);
				$number = $this->formatNumber($refreshSerie, $now);
				$refreshSerie->setCurrentNumber($refreshSerie->getCurrentNumber() + 1);
				$this->connection->table(self::TABLE)->get($refreshSerie->getId())->update(['currentNumber' => $refreshSerie->getCurrentNumber()]);

				if ($selfTransaction) {
					$this->connection->commit();
				}

				return $number;
			} else {
				throw new DriverException('Trait must use with ISeriesModel interface.');
			}
		} catch (\PDOException $exception) {
			if ($selfTransaction) {
				$this->connection->rollBack();
			}
			throw new DriverException($exception->getMessage());
		}
	}
}