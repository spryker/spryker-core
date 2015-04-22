<?php

namespace SprykerFeature\Zed\FrontendExporter\Business;

use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResult;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;

/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class FrontendExporterFacade extends AbstractFacade
{
    /**
     * @param string $locale
     *
     * @return array|BatchResult[]
     */
    public function exportKeyValueForLocale($locale)
    {
        $exporter = $this->getDependencyContainer()->createYvesKeyValueExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param string $locale
     *
     * @return array|BatchResult[]
     */
    public function exportSearchForLocale($locale)
    {
        $exporter = $this->getDependencyContainer()->getYvesSearchExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param string $locale

     * @return array|BatchResult[]
     */
    public function updateSearchForLocale($locale)
    {
        $exporter = $this->getDependencyContainer()->getYvesSearchUpdateExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
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
        return $this->getDependencyContainer()->createSettings()->getSearchIndexName();
    }

    /**
     * @return string
     */
    public function getSearchDocumentType()
    {
        return $this->getDependencyContainer()->createSettings()->getSearchDocumentType();
    }
}
