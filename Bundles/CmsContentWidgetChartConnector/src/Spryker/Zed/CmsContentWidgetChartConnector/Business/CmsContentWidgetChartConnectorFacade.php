<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetChartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsContentWidgetChartConnector\Business\CmsContentWidgetChartConnectorBusinessFactory getFactory()
 */
class CmsContentWidgetChartConnectorFacade extends AbstractFacade implements CmsContentWidgetChartConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $keyList
     *
     * @return array
     */
    public function mapChartKeyList(array $keyList)
    {
        return $this->getFactory()
            ->createCmsChartSkuMapper()
            ->mapChartKeyList($keyList);
    }
}
