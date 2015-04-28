<?php

namespace SprykerFeature\Zed\FrontendExporter\Business;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResult;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;

/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class FrontendExporterFacade extends AbstractFacade
{
    /**
     * @param LocaleDto $locale
     *
     * @return array|BatchResult[]
     */
    public function exportKeyValueForLocale(LocaleDto $locale)
    {
        $exporter = $this->getDependencyContainer()->createYvesKeyValueExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param LocaleDto $locale
     *
     * @return array|BatchResult[]
     */
    public function exportSearchForLocale(LocaleDto $locale)
    {
        $exporter = $this->getDependencyContainer()->getYvesSearchExporter();

        return $exporter->exportForLocale($locale);
    }

    /**
     * @param LocaleDto $locale

     * @return array|BatchResult[]
     */
    public function updateSearchForLocale(LocaleDto $locale)
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
