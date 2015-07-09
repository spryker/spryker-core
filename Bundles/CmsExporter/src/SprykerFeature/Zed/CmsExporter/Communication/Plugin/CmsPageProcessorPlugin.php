<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\CmsExporter\Communication\CmsExporterDependencyContainer;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class CmsPageProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'page';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        $processedResultSet = $this->getDependencyContainer()->getCmsExporterFacade()->buildPages($resultSet, $locale);

        return $processedResultSet;
    }

}
