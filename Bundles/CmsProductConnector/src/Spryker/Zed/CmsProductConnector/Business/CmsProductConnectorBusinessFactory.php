<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductConnector\Business;

use Spryker\Zed\CmsProductConnector\Business\Mapper\CmsProductSkuParameterMapper;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsProductConnector\Persistence\CmsProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsProductConnector\CmsProductConnectorConfig getConfig()
 */
class CmsProductConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsProductConnector\Business\Mapper\CmsProductSkuParameterMapperInterface
     */
    public function createCmsProductSkuMapper()
    {
        return new CmsProductSkuParameterMapper($this->getQueryContainer());
    }

}
