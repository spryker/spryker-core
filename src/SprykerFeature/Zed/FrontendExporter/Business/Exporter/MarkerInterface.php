<?php

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter;

interface MarkerInterface
{
    /**
     * @param string $exportType
     * @param string $locale
     *
     * @return \DateTime
     */
    public function getLastExportMarkByTypeAndLocale($exportType, $locale);

    /**
     * @param string $exportType
     * @param string $locale
     */
    public function setLastExportMarkByTypeAndLocale($exportType, $locale);
}
