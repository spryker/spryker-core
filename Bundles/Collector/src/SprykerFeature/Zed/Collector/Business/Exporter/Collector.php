<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Collector\Business\Exporter\Exception\BatchResultException;
use SprykerFeature\Zed\Collector\Business\Model\BatchResult;
use SprykerFeature\Zed\Collector\Persistence\CollectorQueryContainer;

class Collector
{

    /**
     * @var ExporterInterface
     */
    protected $exporter;

    /**
     * @var CollectorQueryContainer
     */
    protected $queryContainer;

    /**
     * @param CollectorQueryContainer $queryContainer
     * @param ExporterInterface $exporter
     */
    public function __construct(
        CollectorQueryContainer $queryContainer,
        ExporterInterface $exporter
    ) {
        $this->exporter = $exporter;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param LocaleTransfer $locale
     *
     * @return array|BatchResult[]
     */
    public function exportForLocale(LocaleTransfer $locale)
    {
        $types = $this->queryContainer->queryExportTypes()->find();

        $results = [];

        foreach ($types as $type) {
            $result = $this->exporter->exportByType2($type, $locale);
            //$result = $this->exporter->exportByType($type, $locale);

            $this->handleResult($result);

            if ($result instanceof BatchResult) {
                $results[$type] = $result;
            }
        }

        return $results;
    }

    /**
     * @param BatchResult $result
     */
    protected function handleResult(BatchResult $result)
    {
        if ($result->isFailed()) {
            throw new BatchResultException(
                sprintf(
                    'Processed %d from %d for locale %s, where %d failed.',
                    $result->getProcessedCount(),
                    $result->getTotalCount(),
                    $result->getProcessedLocale(),
                    $result->getFailedCount()
                )
            );
        }
    }

}
