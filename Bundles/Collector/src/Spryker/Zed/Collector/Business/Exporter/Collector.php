<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
    public function __construct(TouchQueryContainer $queryContainer, ExporterInterface $exporter)
    {
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
        $results = [];

        $types = array_keys($this->exporter->getCollectorPlugins());
        $availableTypes = $this->queryContainer->queryExportTypes()->find();

        if (isset($output)) {
            $output->writeln('');
            $output->writeln(
                sprintf('<fg=yellow>%d/%d collector(s) executed:</fg=yellow>',
                    count($types),
                    count($availableTypes)
                )
            );
            $output->writeln('');
        }

        foreach ($availableTypes as $type) {
            if (isset($output)) {
                $output->write('<fg=yellow> * </fg=yellow><fg=green>' . $type . '</fg=green><fg=yellow> ');
            }

            if (!in_array($type, $types)) {
                if (isset($output)) {
                    $output->writeln('<fg=white> N/A </fg=white>');
                }
                continue;
            }

            $startTime = microtime(true);

            $result = $this->exporter->exportByType($type, $locale);

            $this->handleResult($result);

            if (isset($output)) {
                if ($result instanceof BatchResultInterface) {
                    $output->write(sprintf(
                        '[%s]',
                        $result->getProcessedCount()
                    ));
                }
                $output->write(' ' . number_format(microtime(true) - $startTime, 4) . 's</fg=yellow>');
                $output->writeln('');
            }

            if ($result instanceof BatchResultInterface) {
                if ($this->nothingWasProcessed($result)) {
                    continue;
                }
                $results[$type] = $result;
            }
        }

        return $results;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     *
     * @return bool
     */
    protected function nothingWasProcessed(BatchResultInterface $result)
    {
        return $result->getProcessedCount() === 0;
    }

    /**
     * @param BatchResultInterface $result
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
