<?php

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface MarkerInterface
{
    /**
     * @param string $exportType
     * @param LocaleDto $locale
     *
     * @return \DateTime
     */
    public function getLastExportMarkByTypeAndLocale($exportType, LocaleDto $locale);

    /**
     * @param string $exportType
     * @param LocaleDto $locale
     */
    public function setLastExportMarkByTypeAndLocale($exportType, LocaleDto $locale);
}
