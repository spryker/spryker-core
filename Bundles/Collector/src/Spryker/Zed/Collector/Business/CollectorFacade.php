<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Collector\Business\Model\BatchResult;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method CollectorBusinessFactory getFactory()
 */
class CollectorFacade extends AbstractFacade
{

    /**
     * @param LocaleTransfer $locale
     * @param OutputInterface|null $output
     *
     * @return BatchResultInterface[]
     */
    public function exportKeyValueForLocale(LocaleTransfer $locale, OutputInterface $output = null)
    {
        $exporter = $this->getFactory()->createYvesKeyValueExporter();

        return $exporter->exportForLocale($locale, $output);
    }

    /**
     * @param LocaleTransfer $locale
     *
     * @return BatchResult[]
     */
    public function exportSearchForLocale(LocaleTransfer $locale)
    {
        $exporter = $this->getFactory()->createYvesSearchExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param LocaleTransfer $locale
     *
     * @return BatchResult[]
     */
    public function updateSearchForLocale(LocaleTransfer $locale)
    {
        $exporter = $this->getFactory()->createYvesSearchUpdateExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->createInstaller($messenger)->install();
    }

    /**
     * @return string
     */
    public function getSearchIndexName()
    {
        return $this->getFactory()->getConfig()->getSearchIndexName();
    }

    /**
     * @return string
     */
    public function getSearchDocumentType()
    {
        return $this->getFactory()->getConfig()->getSearchDocumentType();
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteSearchTimestamps(array $keys = [])
    {
        return $this->getFactory()->createSearchMarker()->deleteTimestamps($keys);
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteStorageTimestamps(array $keys = [])
    {
        return $this->getFactory()->createKeyValueMarker()->deleteTimestamps($keys);
    }

}
