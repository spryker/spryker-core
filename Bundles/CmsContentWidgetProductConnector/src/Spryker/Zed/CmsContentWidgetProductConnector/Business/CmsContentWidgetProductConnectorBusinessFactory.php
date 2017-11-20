<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductConnector\Business;

use Spryker\Zed\CmsContentWidgetProductConnector\Business\Mapper\CmsProductSkuParameterMapper;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductConnector\Persistence\CmsContentWidgetProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorConfig getConfig()
 */
class CmsContentWidgetProductConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsContentWidgetProductConnector\Business\Mapper\CmsProductSkuParameterMapperInterface
     */
    public function createCmsProductSkuMapper()
    {
        return new CmsProductSkuParameterMapper($this->getQueryContainer());
    }
}
