<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UrlExporter\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method UrlExporterDependencyContainer getDependencyContainer()
 */
class UrlExporterFacade extends AbstractFacade
{

    /**
     * @param array $resultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildUrlMap(array $resultSet, LocaleTransfer $locale)
    {
        $redirectUrlMapBuilder = $this->getDependencyContainer()->getUrlMapBuilder();

        return $redirectUrlMapBuilder->buildUrls($resultSet, $locale);
    }

    /**
     * @param array $redirectResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildRedirects(array $redirectResultSet, LocaleTransfer $locale)
    {
        $redirectBuilder = $this->getDependencyContainer()->getRedirectBuilder();

        return $redirectBuilder->buildRedirects($redirectResultSet, $locale);
    }

}
