<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Collector\Business\Model\BatchResult;

/**
 * @method CollectorDependencyContainer getDependencyContainer()
 */
class CollectorFacade extends AbstractFacade
{

    /**
     * @param LocaleTransfer $locale
     *
     * @return array|BatchResult[]
     */
    public function exportKeyValueForLocale(LocaleTransfer $locale)
    {
        $exporter = $this->getDependencyContainer()->createYvesKeyValueExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param LocaleTransfer $locale
     *
     * @return array|BatchResult[]
     */
    public function exportSearchForLocale(LocaleTransfer $locale)
    {
        $exporter = $this->getDependencyContainer()->getYvesSearchExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param LocaleTransfer $locale

     *
     * @return array|BatchResult[]
     */
    public function updateSearchForLocale(LocaleTransfer $locale)
    {
        $exporter = $this->getDependencyContainer()->getYvesSearchUpdateExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param MessengerInterface $messenger
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getDependencyContainer()->createInstaller($messenger)->install();
    }

    /**
     * @return string
     */
    public function getSearchIndexName()
    {
        return $this->getDependencyContainer()->getConfig()->getSearchIndexName();
    }

    /**
     * @return string
     */
    public function getSearchDocumentType()
    {
        return $this->getDependencyContainer()->getConfig()->getSearchDocumentType();
    }

}
