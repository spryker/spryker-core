<?php

namespace SprykerFeature\Zed\UrlExporter\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method UrlExporterDependencyContainer getDependencyContainer()
 */
class UrlExporterFacade extends AbstractFacade
{
    /**
     * @param array $resultSet
     * @param string $locale
     *
     * @return array
     */
    public function buildUrlMap(array $resultSet, $locale)
    {
        $redirectUrlMapBuilder = $this->getDependencyContainer()->getUrlMapBuilder();

        return $redirectUrlMapBuilder->buildUrls($resultSet, $locale);
    }

    /**
     * @param array $redirectResultSet
     * @param string $locale
     *
     * @return array
     */
    public function buildRedirects(array $redirectResultSet, $locale)
    {
        $redirectBuilder = $this->getDependencyContainer()->getRedirectBuilder();

        return $redirectBuilder->buildRedirects($redirectResultSet, $locale);
    }
}
