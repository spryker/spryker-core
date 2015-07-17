<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\CmsExporter\Communication\CmsExporterDependencyContainer;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class CmsQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'page';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $queryContainer = $this->getDependencyContainer()->getCmsExporterQueryContainer();

        return $queryContainer->expandCmsPageQuery($expandableQuery);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 500;
    }

}
