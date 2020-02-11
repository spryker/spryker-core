<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\Business\CmsContentWidgetProductSetConnectorBusinessFactory getFactory()
 */
class CmsContentWidgetProductSetConnectorFacade extends AbstractFacade implements CmsContentWidgetProductSetConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $keyList
     *
     * @return array
     */
    public function mapProductKeyList(array $keyList)
    {
        return $this->getFactory()
            ->createCmsProductSkuMapper()
            ->mapProductSetKeyList($keyList);
    }
}
