<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Collector\Business\Exporter\Exception\BatchResultException;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Intl\Locale;

class Collector
{

    /**
     * @var TouchQueryContainer
     */
    protected $touchQueryContainer;

    /**
     * @var ExporterInterface
     */
    protected $exporter;

    /**
     * @var CollectorToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param TouchQueryContainer $touchQueryContainer
     * @param CollectorToLocaleInterface $localeFacade
     * @param ExporterInterface $exporter
     */
    public function __construct(TouchQueryContainer $touchQueryContainer, CollectorToLocaleInterface $localeFacade, ExporterInterface $exporter)
    {
        $this->touchQueryContainer = $touchQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->exporter = $exporter;
    }

    /**
     * @param LocaleTransfer $locale
     *
     * @return BatchResultInterface[]
     */
    public function exportForLocale(LocaleTransfer $locale, OutputInterface $output = null)
    {
        $results = [];
        $types = array_keys($this->exporter->getCollectorPlugins());
        $availableTypes = $this->touchQueryContainer->queryExportTypes()->find();

        if (isset($output)) {
            $output->writeln('');
            $output->writeln(
                sprintf('<fg=yellow>Locale:</fg=yellow> <fg=white>%s</fg=white>',
                    $locale->getLocaleName()
                )
            );
            $output->writeln('<fg=yellow>-------------</fg=yellow>');
        }

        foreach ($availableTypes as $type) {
            if (!in_array($type, $types)) {
                if (isset($output)) {
                    $output->write('<fg=yellow> * </fg=yellow><fg=green>' . $type . '</fg=green> ');
                    $output->write('<fg=white>N/A</fg=white>');
                    $output->writeln('');
                }
                continue;
            }

            $result = $this->exporter->exportByType($type, $locale, $output);

            $this->handleResult($result);

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
     * @param OutputInterface|null $output
     *
     * @return array
     */
    public function exportForStorage(OutputInterface $output = null)
    {
        $locales = Store::getInstance()->getLocales();

        $results = [];

        $types = array_keys($this->exporter->getCollectorPlugins());
        $availableTypes = $this->touchQueryContainer->queryExportTypes()->find();

        sprintf('<fg=yellow>%d out of %d collectors available:</fg=yellow>',
            count($types),
            count($availableTypes)
        );

        foreach ($locales as $locale) {
            $localeTransfer = $this->localeFacade->getLocale($locale);
            $results[$locale] = $this->exportForLocale($localeTransfer, $output);
        }

        return $results;
    }

    /**
     * @param BatchResultInterface $result
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

    /**
     * @return array
     */
    public function getAllCollectorTypes()
    {
        return $this->touchQueryContainer
            ->queryExportTypes()
            ->setFormatter(new SimpleArrayFormatter())
            ->find()
            ->toArray();
    }

    /**
     * @return array
     */
    public function getEnabledCollectorTypes()
    {
        return array_keys($this->exporter->getCollectorPlugins());
    }

}
