<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CmsCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;

/**
 * @method CmsCommunication getFactory()
 */
class CmsDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function createCmsGrid(Request $request)
    {
        return $this->getFactory()->createGridCmsGrid(
            $this->getQueryContainer()->queryPagesWithTemplates(),
            $request
        );
    }

    /**
     * @return CmsQueryContainer
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->cms()->queryContainer();
    }

    /**
     * @return OrdersTable
     */
    public function createCmsTable()
    {
        $pageQuery = $this->getQueryContainer()->queryPageWithTemplatesAndUrls();

        return $this->getFactory()->createTableCmsTable($pageQuery);
    }

    /**
     * @param string $type
     *
     * @return CmsPageForm
     */
    public function createCmsPageForm($type)
    {
        $templateQuery = $this->getQueryContainer()
            ->queryTemplates();

        return $this->getFactory()
            ->createFormCmsPageForm($templateQuery ,$type)
            ;
    }

    /**
     * @param string $type
     *
     * @return CmsRedirectForm
     */
    public function createCmsRedirectForm($type)
    {
        return $this->getFactory()
            ->createFormCmsRedirectForm($type)
            ;
    }
}
