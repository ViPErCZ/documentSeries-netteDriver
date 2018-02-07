<?php

namespace ViPErCZ\DocumentSeries\Models\Drivers\Nette;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use ViPErCZ\DocumentSeries\DriverException;
use ViPErCZ\DocumentSeries\Entity\Serie;
use ViPErCZ\DocumentSeries\Models\IGenerate;
use ViPErCZ\DocumentSeries\Models\ISeriesModel;

/**
 * Class SeriesModel
 * @package ViPErCZ\DocumentSeries\Models\Drivers\Nette
 */
final class SeriesModel implements ISeriesModel, IGenerate {

	use GenerateTrait;

	/** @var Context */
	private $connection;

	/** Table name */
	private const TABLE = 'documentSeries';

	/**
	 * SeriesModel constructor.
	 * @param Context $connection
	 */
	public function __construct(Context $connection) {
		$this->connection = $connection;
	}

	/**
	 * @param $id
	 * @return null|Serie
	 * @throws DriverException
	 */
	public function getSerie($id): ?Serie {
		try {
			$serie = $this->connection->table(self::TABLE)->get($id);
			if ($serie) {
				return $this->createEntity($serie);
			} else {
				return null;
			}
		} catch (\PDOException $exception) {
			throw new DriverException($exception->getMessage());
		}
	}

	/**
	 * @param $accountingYearId
	 * @param null $documentType
	 * @return array
	 * @throws DriverException
	 */
	public function getSeries($accountingYearId, $documentType = null): array {
		try {
			/** @var Selection $selection */
			$selection = $this->connection->table(self::TABLE)
				->where('accountingYearId', $accountingYearId)
				->where('documentType', $documentType);

			$result = [];
			foreach ($selection as $item) {
				$result[] = $this->createEntity($item);
			}

			return $result;
		} catch (\PDOException $exception) {
			throw new DriverException($exception->getMessage());
		}
	}

	/**
	 * @param $accountingYearId
	 * @param $documentType
	 * @return null|Serie
	 * @throws DriverException
	 */
	public function getSerieForRobots($accountingYearId, $documentType): ?Serie {
		try {
			$serie = $this->connection->table(self::TABLE)
				->where('accountingYearId', $accountingYearId)
				->where('documentType', $documentType)
				->where('forRobots', true)
				->limit(1)
				->fetch();
			if ($serie) {
				return $this->createEntity($serie);
			} else {
				return null;
			}
		} catch (\PDOException $exception) {
			throw new DriverException($exception->getMessage());
		}
	}

	/**
	 * @param ActiveRow $serie
	 * @return Serie
	 */
	private function createEntity(ActiveRow $serie): Serie {
		$entity = new Serie();
		$entity->setId($serie->id);
		$entity->setAccountingYearId($serie->accountingYearId);
		$entity->setCurrentNumber($serie->currentNumber);
		$entity->setDateInserted($serie->dateInserted);
		$entity->setMask($serie->mask);
		$entity->setPrefix($serie->prefix);
		$entity->setDocumentType($serie->documentType);
		$entity->setForRobots($serie->forRobots);
		$entity->setResetBy($serie->resetBy);
		$entity->setNumbers($serie->numbers);
		$entity->setDateUpdated($serie->dateUpdated);
		$entity->setLastUse($serie->lastUse);

		return $entity;
	}
}