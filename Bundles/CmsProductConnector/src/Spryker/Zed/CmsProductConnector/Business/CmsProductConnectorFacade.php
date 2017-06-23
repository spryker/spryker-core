<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsProductConnector\Business\CmsProductConnectorBusinessFactory getFactory()
 */
class CmsProductConnectorFacade extends AbstractFacade implements CmsProductConnectorFacadeInterface
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
    public function mapProductSkuList(array $skuList)
    {
        return $this->getFactory()
            ->createCmsProductSkuMapper()
            ->mapProductSkuList($skuList);
    }

}
