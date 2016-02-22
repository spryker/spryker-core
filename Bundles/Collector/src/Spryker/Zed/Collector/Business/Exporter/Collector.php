<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Collector\Business\Exporter\Exception\BatchResultException;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Collector
{

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainer
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\ExporterInterface
     */
    protected $exporter;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainer $queryContainer
     * @param \Spryker\Zed\Collector\Business\Exporter\ExporterInterface $exporter
     */
    public function __construct(
        TouchQueryContainer $queryContainer,
        ExporterInterface $exporter
    ) {
        $this->queryContainer = $queryContainer;
        $this->exporter = $exporter;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function exportForLocale(LocaleTransfer $locale, OutputInterface $output = null)
    {
        $types = $this->queryContainer->queryExportTypes()->find();

        $results = [];

        foreach ($types as $type) {
            $startTime = microtime(true);

            if (isset($output)) {
                $output->writeln('Started export for type: ' . $type);
            }

            $result = $this->exporter->exportByType($type, $locale);

            $this->handleResult($result);

            if (isset($output)) {
                $output->writeln('Finished export for type: ' . $type . ' after ' . number_format(microtime(true) - $startTime, 4) . ' s');
            }

            if ($result instanceof BatchResultInterface) {
                $results[$type] = $result;
            }
        }

        return $results;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     *
     * @return void
     */
    protected function handleResult(BatchResultInterface $result)
    {
        if ($result->isFailed()) {
            throw new BatchResultException(
                sprintf(
                    'Processed %d from %d for locale %s, where %d were deleted and %d failed.',
                    $result->getProcessedCount(),
                    $result->getTotalCount(),
                    $result->getProcessedLocale(),
                    $result->getDeletedCount(),
                    $result->getFailedCount()
                )
            );
        }
    }

}
