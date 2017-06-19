<?php

namespace Spryker\Zed\CmsBlockProductConnector\Business;

use Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractWriter;
use Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorQueryContainerInterface;

/**
 * @method CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 */
class CmsBlockProductConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CmsBlockProductAbstractWriterInterface
     */
    public function createCmsBlockProductAbstractWriter()
    {
        return new CmsBlockProductAbstractWriter(
            $this->getQueryContainer()
        );
    }

}