<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Collector\Business\Exporter\Exception\BatchResultException;
use Spryker\Zed\Collector\Business\Exporter\Exception\UndefinedCollectorTypesException;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Intl\Locale;

class Collector
{

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainer
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\ExporterInterface
     */
    protected $exporter;

    /**
     * @var \Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var array
     */
    protected $availableCollectorTypes;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainer $touchQueryContainer
     * @param \Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Collector\Business\Exporter\ExporterInterface $exporter
     * @param array $availableCollectorTypes
     */
    public function __construct(TouchQueryContainer $touchQueryContainer, CollectorToLocaleInterface $localeFacade, ExporterInterface $exporter, array $availableCollectorTypes)
    {
        $this->touchQueryContainer = $touchQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->exporter = $exporter;
        $this->availableCollectorTypes = $availableCollectorTypes;
    }

    /**
     * @param \Spryker\Zed\Locale\Business\LocaleFacade $locale
     *
     * @return BatchResultInterface[]
     */
    public function exportForLocale(LocaleTransfer $locale, OutputInterface $output = null)
    {
        $results = [];
        $types = array_keys($this->exporter->getCollectorPlugins());

        if (empty($this->availableCollectorTypes)) {
            throw new UndefinedCollectorTypesException();
        }

        if (isset($output)) {
            $output->writeln('');
            $output->writeln(
                sprintf('<fg=yellow>Locale:</fg=yellow> <fg=white>%s</fg=white>',
                    $locale->getLocaleName()
                )
            );
            $output->writeln('<fg=yellow>-------------</fg=yellow>');
        }

        foreach ($this->availableCollectorTypes as $type) {
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
     * @param \Symfony\Component\Console\Output\OutputInterface|null $output
     *
     * @return array
     */
    public function exportForStorage(OutputInterface $output = null)
    {
        $locales = Store::getInstance()->getLocales();

        $results = [];

        $types = array_keys($this->exporter->getCollectorPlugins());

        sprintf('<fg=yellow>%d out of %d collectors available:</fg=yellow>',
            count($types),
            count($this->availableCollectorTypes)
        );

        foreach ($locales as $locale) {
            $localeTransfer = $this->localeFacade->getLocale($locale);
            $results[$locale] = $this->exportForLocale($localeTransfer, $output);
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
