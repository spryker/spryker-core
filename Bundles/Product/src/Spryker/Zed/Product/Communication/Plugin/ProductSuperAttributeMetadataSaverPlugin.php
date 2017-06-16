<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductMetadataSaverInterface;

/**
 * @method \Spryker\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 * @method \Spryker\Zed\Product\Business\ProductFacade getFacade()
 */
class ProductSuperAttributeMetadataSaverPlugin extends AbstractPlugin implements ProductMetadataSaverInterface
{

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveProductMetadata(QuoteTransfer $quoteTransfer)
    {
        $this->getFacade()->saveSuperAttributeMetadata($quoteTransfer);
    }

}
