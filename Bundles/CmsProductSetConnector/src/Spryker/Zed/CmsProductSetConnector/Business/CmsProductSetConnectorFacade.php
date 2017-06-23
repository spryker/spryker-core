<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductSetConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsProductSetConnector\Business\CmsProductSetConnectorBusinessFactory getFactory()
 */
class CmsProductSetConnectorFacade extends AbstractFacade implements CmsProductSetConnectorFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $skuList
     *
     * @return array
     */
    public function mapProductKeyList(array $skuList)
    {
        return $this->getFactory()
            ->createCmsProductSkuMapper()
            ->mapProductSetKeyList($skuList);
    }

}
