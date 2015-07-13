<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Exception\ProcessException;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Exception\WriteException;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Model\FailedResultInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\ExportFailedDeciderPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Persistence\FrontendExporterQueryContainer;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;

abstract class AbstractExporter implements ExporterInterface
{

    /**
     * @var DataProcessorPluginInterface[]
     */
    protected $dataProcessorPipeline = [];
    /**
     * @var FailedResultInterface
     */
    protected $failedResultPrototype;
    /**
     * @var BatchResultInterface
     */
    protected $batchResultPrototype;

    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * @var MarkerInterface
     */
    protected $marker;
    /**
     * @var FrontendExporterQueryContainer
     */
    protected $queryContainer;

    /**
     * @var QueryExpanderPluginInterface[]
     */
    protected $queryPipeline = [];

    /**
     * @var ExportFailedDeciderPluginInterface[]
     */

    protected $decider = [];
    /**
     * @var int
     */
    private $standardChunkSize = 1000;

    /**
     * @var array
     */
    private $chunkSizeTypeMap = [];

    /**
     * @param FrontendExporterQueryContainer $queryContainer
     * @param WriterInterface $writer
     * @param MarkerInterface $marker
     * @param FailedResultInterface $failedResultPrototype
     * @param BatchResultInterface $batchResultPrototype
     */
    public function __construct(
        FrontendExporterQueryContainer $queryContainer,
        WriterInterface $writer,
        MarkerInterface $marker,
        FailedResultInterface $failedResultPrototype,
        BatchResultInterface $batchResultPrototype
    )
    {
        $this->writer = $writer;
        $this->failedResultPrototype = $failedResultPrototype;
        $this->batchResultPrototype = $batchResultPrototype;
        $this->marker = $marker;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param DataProcessorPluginInterface $processor
     */
    public function addDataProcessor(DataProcessorPluginInterface $processor)
    {
        $this->dataProcessorPipeline[$processor->getProcessableType()][] = $processor;
    }

    /**
     * @param QueryExpanderPluginInterface $queryExpander
     */
    public function addQueryExpander(QueryExpanderPluginInterface $queryExpander)
    {
        $this->queryPipeline[$queryExpander->getProcessableType()][] = $queryExpander;
    }

    /**
     * @param ExportFailedDeciderPluginInterface $decider
     */
    public function addDecider(ExportFailedDeciderPluginInterface $decider)
    {
        $this->decider[$decider->getProcessableType()] = $decider;
    }

    /**
     * @param string $type
     * @param LocaleTransfer $locale
     *
     * @return BatchResultInterface|null
     */
    public function exportByType($type, LocaleTransfer $locale)
    {
        $result = clone $this->batchResultPrototype;
        $result->setProcessedLocale($locale);

        if (!array_key_exists($type, $this->dataProcessorPipeline) ||
            !array_key_exists($type, $this->queryPipeline)
        ) {
            $result->setProcessedCount(0);
            $result->setIsFailed(false);
            $result->setTotalCount(0);

            return $result;
        }

        $lastRunDatetime = $this->marker->getLastExportMarkByTypeAndLocale($type, $locale);

        $resultSets = $this->buildResultIteratorForType($locale, $type, $lastRunDatetime);
        $result->setTotalCount($resultSets->count());

        foreach ($resultSets as $resultSet) {
            $result->setFetchedCount(count($resultSet));

            $exportableData = $this->processData($resultSet, $type, $locale);
            $this->writeExportableData($exportableData, $type);
            $result->increaseProcessedCount(count($exportableData));

            if ($this->isExportFailed($type, $result)) {
                $result->setIsFailed(true);

                return $result;
            }
        }

        return $this->finishExport($result, $type);
    }

    /**
     * @param array $exportData
     * @param string $type
     *
     * @throw WriteException
     */
    protected function writeExportableData(array $exportData, $type = '')
    {
        if (!empty($exportData)) {
            $writeResult = $this->writer->write($exportData, $type);

            if (!$writeResult) {
                throw new WriteException(sprintf('%s could not write', $this->writer->getName()));
            }
        }
    }

    /**
     * @param ProcessException $exception
     * @param array $ids
     *
     * @return FailedResultInterface
     */
    protected function createFailedResult(ProcessException $exception, array $ids)
    {
        $failedResult = clone $this->failedResultPrototype;
        $failedResult->setFirstId(array_shift($ids));
        $failedResult->setLastId(array_pop($ids));
        $failedResult->setFailedCount(count($ids));
        $failedResult->setReason($exception->getMessage());

        return $failedResult;
    }

    /**
     * @param BatchResultInterface $batchResult
     * @param string $type
     *
     * @return BatchResultInterface
     */
    protected function finishExport(BatchResultInterface $batchResult, $type)
    {
        if (!$batchResult->isFailed()) {
            $this->marker->setLastExportMarkByTypeAndLocale($type, $batchResult->getProcessedLocale());
        }

        return $batchResult;
    }

    /**
     * @param string $locale
     * @param string $type
     * @param \DateTime $lastRunTimestamp
     *
     * @return BatchIterator
     */
    protected function buildResultIteratorForType($locale, $type, \DateTime $lastRunTimestamp)
    {
        $query = $this->queryContainer->createBasicExportableQuery($type, $lastRunTimestamp);
        $query->setFormatter(new PropelArraySetFormatter());

        /** @var QueryExpanderPluginInterface $queryExpander */
        foreach ($this->queryPipeline[$type] as $queryExpander) {
            $query = $queryExpander->expandQuery($query, $locale);
        }

        if (array_key_exists($type, $this->chunkSizeTypeMap)) {
            $chunkSize = $this->chunkSizeTypeMap[$type];
        } else {
            $chunkSize = $this->standardChunkSize;
        }

        return new BatchIterator($query, $chunkSize);
    }

    /**
     * @param string $type
     * @param BatchResultInterface $result
     *
     * @return bool
     */
    protected function isExportFailed($type, $result)
    {
        if (array_key_exists($type, $this->decider)) {
            $decider = $this->decider[$type];

            return $decider->isFailed($result);
        }

        return false;
    }

    /**
     * @param array $resultSet
     * @param string $type
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    protected function processData($resultSet, $type, LocaleTransfer $locale)
    {
        $processedResultSet = [];

        if (array_key_exists($type, $this->dataProcessorPipeline)) {
            /** @var DataProcessorPluginInterface $dataProcessor */
            foreach ($this->dataProcessorPipeline[$type] as $dataProcessor) {
                $processedResultSet = $dataProcessor->processData($resultSet, $processedResultSet, $locale);
            }
        }

        return $processedResultSet;
    }

    /**
     * @param int $standardChunkSize
     *
     * @return $this
     */
    public function setStandardChunkSize($standardChunkSize)
    {
        $this->standardChunkSize = $standardChunkSize;

        return $this;
    }

    /**
     * @param array $chunkSizeTypeMap
     *
     * @return $this
     */
    public function setChunkSizeTypeMap(array $chunkSizeTypeMap)
    {
        $this->chunkSizeTypeMap = $chunkSizeTypeMap;

        return $this;
    }

    /**
     * @param string $type
     * @param int $chunkSize
     *
     * @return $this
     */
    public function setChunkSizeForType($type, $chunkSize)
    {
        $this->chunkSizeTypeMap[$type] = $chunkSize;

        return $this;
    }

}
