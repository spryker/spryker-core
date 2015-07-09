<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Exception\BatchResultException;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResult;
use SprykerFeature\Zed\FrontendExporter\Persistence\FrontendExporterQueryContainer;

class FrontendExporter
{

    /**
     * @var ExporterInterface
     */
    protected $exporter;

    /**
     * @var FrontendExporterQueryContainer
     */
    protected $queryContainer;

    /**
     * @param FrontendExporterQueryContainer  $queryContainer
     * @param ExporterInterface         $exporter
     */
    public function __construct(
        FrontendExporterQueryContainer $queryContainer,
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
            $result = $this->exporter->exportByType($type, $locale);

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
