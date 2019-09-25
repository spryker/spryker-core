<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchBusinessFactory getFactory()
 */
class ProductSetPageSearchFacade extends AbstractFacade implements ProductSetPageSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productSetIds
     *
     * @return void
     */
    public function publish(array $productSetIds)
    {
        $this->getFactory()->createProductSetPageSearchWriter()->publish($productSetIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productSetIds
     *
     * @return void
     */
    public function unpublish(array $productSetIds)
    {
        $this->getFactory()->createProductSetPageSearchWriter()->unpublish($productSetIds);
    }
}
