<?php

require_once __DIR__ . '/bootstrap.php';

try {
	$dsn = 'mysql:dbname=documentSerie;host=172.17.0.1';
	$connection = new \Nette\Database\Connection($dsn, 'tester', 'tester');

	$structure = new \Nette\Database\Structure($connection, new \Nette\Caching\Storages\DevNullStorage());
	$convention = new Nette\Database\Conventions\DiscoveredConventions($structure);
	$context = new \Nette\Database\Context($connection, $structure, $convention);
	$result = $context->query('SHOW TABLES')->fetch();
	var_dump($result);

	$driverBuilder = new \ViPErCZ\DocumentSeries\Models\DriverBuilder(new \ViPErCZ\DocumentSeries\Models\Drivers\NetteDriverList(), $context);
	$seriesOperator = new \ViPErCZ\DocumentSeries\SeriesOperator($driverBuilder);

	$serie = $seriesOperator->getSerie(1);
	var_dump($serie);

	if ($serie) {
		$number = $seriesOperator->generateNumber($serie);
		echo 'NUMBER: ' . $number . PHP_EOL;
	}

	$accountingYear = $seriesOperator->getAccountingYear(1);
	var_dump($accountingYear);

	$accountingYear = \ViPErCZ\DocumentSeries\Entity\AccountingYear::create(3, 2020, true);
	$accountingYearTest = $seriesOperator->getYear(new DateTime('2020-01-01 00:00:00'));
	var_dump($accountingYearTest);
	$seriesOperator->insertAccountingYear($accountingYear);
	$accountingYearTest = $seriesOperator->getYear(new DateTime('2020-01-01 00:00:00'));
	var_dump($accountingYearTest);

	$seriesOperator->insertAccountingYear($accountingYear); // exception violation

} catch (\ViPErCZ\DocumentSeries\DriverException $exception) {
	echo $exception->getMessage() . PHP_EOL;
}