<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class MetadataManager implements MetadataManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductMetadataSaverInterface[]
     */
    protected $metadataSaverStack;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductMetadataHydratorInterface[]
     */
    protected $metadataHydratorStack;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductMetadataSaverInterface[] $metadataSaverStack
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductMetadataHydratorInterface[] $metadataHydratorStack
     */
    public function __construct(array $metadataSaverStack, array $metadataHydratorStack)
    {
        $this->metadataSaverStack = $metadataSaverStack;
        $this->metadataHydratorStack = $metadataHydratorStack;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrderInformation(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        foreach ($this->metadataSaverStack as $metadataSaver) {
            $metadataSaver->saveProductMetadata($quoteTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderInformation(OrderTransfer $orderTransfer)
    {
        foreach ($this->metadataHydratorStack as $metadataHydrator) {
            $orderTransfer = $metadataHydrator->hydrateProductMetadata($orderTransfer);
        }

        return $orderTransfer;
    }

}
