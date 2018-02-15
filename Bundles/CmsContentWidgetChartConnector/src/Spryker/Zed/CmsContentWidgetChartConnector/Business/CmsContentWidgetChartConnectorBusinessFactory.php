<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetChartConnector\Business;

use Spryker\Zed\CmsContentWidgetChartConnector\Business\Mapper\CmsChartKeyMapperPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsContentWidgetChartConnector\CmsContentWidgetChartConnectorConfig getConfig()
 */
class CmsContentWidgetChartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsContentWidgetChartConnector\Business\Mapper\CmsChartKeyMapperPluginInterface
     */
    public function createCmsChartSkuMapper()
    {
        return new CmsChartKeyMapperPlugin();
    }
}
