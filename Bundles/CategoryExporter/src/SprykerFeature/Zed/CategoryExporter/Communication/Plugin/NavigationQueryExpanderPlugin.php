<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Category\CategoryConfig;
use SprykerFeature\Zed\CategoryExporter\Business\CategoryExporterFacade;
use SprykerFeature\Zed\CategoryExporter\Communication\CategoryExporterDependencyContainer;
use SprykerFeature\Zed\Collector\Dependency\Plugin\QueryExpanderPluginInterface;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 * @method CategoryExporterFacade getFacade()
 */
class NavigationQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return CategoryConfig::RESOURCE_TYPE_NAVIGATION;
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        return $this->getFacade()->expandNavigationQuery($expandableQuery, $locale);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }

}
