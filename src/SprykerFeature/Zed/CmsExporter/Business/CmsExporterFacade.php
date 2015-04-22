<?php

namespace SprykerFeature\Zed\CmsExporter\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class CmsExporterFacade extends AbstractFacade
{
    /**
     * @param array $pageResultSet
     * @param string $locale
     *
     * @return array
     */
    public function buildPages(array $pageResultSet, $locale)
    {
        $pageBuilder = $this->getDependencyContainer()->createPageBuilder();

        return $pageBuilder->buildPages($pageResultSet, $locale);
    }
}
