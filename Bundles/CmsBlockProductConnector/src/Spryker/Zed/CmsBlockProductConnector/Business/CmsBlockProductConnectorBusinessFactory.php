<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business;

use Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorConfig getConfig()
 */
class CmsBlockProductConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractWriterInterface
     */
    public function createCmsBlockProductAbstractWriter()
    {
        return new CmsBlockProductAbstractWriter(
            $this->getQueryContainer()
        );
    }

}
