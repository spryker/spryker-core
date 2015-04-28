<?php

namespace SprykerFeature\Zed\UrlExporter\Business;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method UrlExporterDependencyContainer getDependencyContainer()
 */
class UrlExporterFacade extends AbstractFacade
{
    /**
     * @param array $resultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function buildUrlMap(array $resultSet, LocaleDto $locale)
    {
        $redirectUrlMapBuilder = $this->getDependencyContainer()->getUrlMapBuilder();

        return $redirectUrlMapBuilder->buildUrls($resultSet, $locale);
    }

    /**
     * @param array $redirectResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function buildRedirects(array $redirectResultSet, LocaleDto $locale)
    {
        $redirectBuilder = $this->getDependencyContainer()->getRedirectBuilder();

        return $redirectBuilder->buildRedirects($redirectResultSet, $locale);
    }
}
