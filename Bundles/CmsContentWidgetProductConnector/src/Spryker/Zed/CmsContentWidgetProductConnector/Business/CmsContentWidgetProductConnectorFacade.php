<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductConnector\Business\CmsContentWidgetProductConnectorBusinessFactory getFactory()
 */
class CmsContentWidgetProductConnectorFacade extends AbstractFacade implements CmsContentWidgetProductConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $skuList
     *
     * @return array
     */
    public function mapProductSkuList(array $skuList)
    {
        return $this->getFactory()
            ->createCmsProductSkuMapper()
            ->mapProductSkuList($skuList);
    }
}
