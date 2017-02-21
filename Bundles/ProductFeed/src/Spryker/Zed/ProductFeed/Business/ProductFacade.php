<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductFeed\Business;

use Generated\Shared\Transfer\ProductFeedConditionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductFeed\Business\ProductFeedBusinessFactory getFactory()
 */
class ProductFeedFacade extends AbstractFacade implements ProductFeedFacadeInterface
{

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductFeedConditionTransfer $productFeedConditionTransfer
     *
     * @return array
     */
    public function getProductFeed(ProductFeedConditionTransfer $productFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createProductExporter()
            ->getProductFeed($productFeedConditionTransfer);
    }

}
