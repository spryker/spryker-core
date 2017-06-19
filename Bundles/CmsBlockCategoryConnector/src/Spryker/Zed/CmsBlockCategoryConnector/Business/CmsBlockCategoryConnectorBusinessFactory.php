<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Business;


use Spryker\Zed\CmsBlockCategoryConnector\Business\Model\CmsBlockCategoryWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

/**
 * @method CmsBlockCategoryConnectorQueryContainerInterface getQueryContainer()
 */
class CmsBlockCategoryConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CmsBlockCategoryWriter
     */
    public function createCmsBlockCategoryWrite()
    {
        return new CmsBlockCategoryWriter(
            $this->getQueryContainer()
        );
    }

}