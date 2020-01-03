<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Collector\Business\Exporter\Exception\BatchResultException;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface;
use Spryker\Zed\Collector\Dependency\Facade\CollectorToStoreFacadeInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CollectorExporter
{
    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
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
     * @var \Spryker\Zed\Collector\Dependency\Facade\CollectorToStoreFacadeInterface|null
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     * @param \Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Collector\Business\Exporter\ExporterInterface $exporter
     * @param \Spryker\Zed\Collector\Dependency\Facade\CollectorToStoreFacadeInterface|null $storeFacade
     */
    public function __construct(
        TouchQueryContainerInterface $touchQueryContainer,
        CollectorToLocaleInterface $localeFacade,
        ExporterInterface $exporter,
        ?CollectorToStoreFacadeInterface $storeFacade = null
    ) {
        $this->touchQueryContainer = $touchQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->exporter = $exporter;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return array
     */
    public function exportStorageByLocale(LocaleTransfer $locale, OutputInterface $output)
    {
        $results = [];
        $types = array_keys($this->exporter->getCollectorPlugins());
        $availableTypes = $this->getAvailableCollectorTypes($types);

        $output->write(PHP_EOL);
        $output->writeln(sprintf('<fg=yellow>Locale:</fg=yellow> <fg=white>%s</fg=white>', $locale->getLocaleName()));
        $output->writeln('<fg=yellow>-------------</fg=yellow>');

        foreach ($availableTypes as $type) {
            if (!in_array($type, $types)) {
                $output->writeln('<fg=yellow> * </fg=yellow><fg=green>' . $type . '</fg=green> <fg=white>N/A</fg=white>');
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
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return array
     */
    public function exportStorage(OutputInterface $output)
    {
        $storeName = $this->getStoreName();

        $results = [];

        $output->writeln('<fg=yellow>----------------------------------------</fg=yellow>');
        $output->writeln(sprintf(
            '<fg=yellow>Exporting Store:</fg=yellow> <fg=white>%s</fg=white>',
            $storeName
        ));

        $localeCollection = $this->getLocalesForStore();

        foreach ($localeCollection as $localeCode) {
            $localeTransfer = $this->localeFacade->getLocale($localeCode);
            $results[$storeName . '@' . $localeCode] = $this->exportStorageByLocale($localeTransfer, $output);
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
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\BatchResultException
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
                    $result->getProcessedLocale() ? $result->getProcessedLocale()->getLocaleName() : null,
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

    /**
     * @param array $types
     *
     * @return array
     */
    protected function getAvailableCollectorTypes(array $types)
    {
        $availableTypes = $this->touchQueryContainer
            ->queryExportTypes()
            ->find();

        return array_unique(array_merge($types, $availableTypes));
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        if ($this->storeFacade) {
            return $this->storeFacade->getCurrentStore()->getName();
        }

        return Store::getInstance()->getStoreName();
    }

    /**
     * @return array
     */
    protected function getLocalesForStore()
    {
        if ($this->storeFacade) {
            return $this->storeFacade->getCurrentStore()->getAvailableLocaleIsoCodes();
        }

        return Store::getInstance()->getLocalesPerStore($this->getStoreName());
    }
}
