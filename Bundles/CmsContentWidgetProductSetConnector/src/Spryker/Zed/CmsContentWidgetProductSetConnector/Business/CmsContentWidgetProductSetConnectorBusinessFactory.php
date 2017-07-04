<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector\Business;

use Spryker\Zed\CmsContentWidgetProductSetConnector\Business\Mapper\CmsProductSetKeyParameterMapper;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence\CmsContentWidgetProductSetConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorConfig getConfig()
 */
class CmsContentWidgetProductSetConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsContentWidgetProductSetConnector\Business\Mapper\CmsProductSetKeyParameterMapperInterface
     */
    public function createCmsProductSkuMapper()
    {
        return new CmsProductSetKeyParameterMapper($this->getQueryContainer());
    }

}
