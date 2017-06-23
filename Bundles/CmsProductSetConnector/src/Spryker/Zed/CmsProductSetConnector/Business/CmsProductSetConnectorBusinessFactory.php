<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductSetConnector\Business;

use Spryker\Zed\CmsProductSetConnector\Business\Mapper\CmsProductSetKeyParameterMapper;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsProductSetConnector\Persistence\CmsProductSetConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsProductSetConnector\CmsProductSetConnectorConfig getConfig()
 */
class CmsProductSetConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsProductSetConnector\Business\Mapper\CmsProductSetKeyParameterMapperInterface
     */
    public function createCmsProductSkuMapper()
    {
        return new CmsProductSetKeyParameterMapper($this->getQueryContainer());
    }

}
