<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class CmsExporterFacade extends AbstractFacade
{

    /**
     * @param array $pageResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildPages(array $pageResultSet, LocaleTransfer $locale)
    {
        $pageBuilder = $this->getDependencyContainer()->createPageBuilder();

        return $pageBuilder->buildPages($pageResultSet, $locale);
    }

}
