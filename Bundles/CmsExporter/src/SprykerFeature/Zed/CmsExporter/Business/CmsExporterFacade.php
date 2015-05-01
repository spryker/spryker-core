<?php

namespace SprykerFeature\Zed\CmsExporter\Business;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class CmsExporterFacade extends AbstractFacade
{
    /**
     * @param array $pageResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function buildPages(array $pageResultSet, LocaleDto $locale)
    {
        $pageBuilder = $this->getDependencyContainer()->createPageBuilder();

        return $pageBuilder->buildPages($pageResultSet, $locale);
    }
}
