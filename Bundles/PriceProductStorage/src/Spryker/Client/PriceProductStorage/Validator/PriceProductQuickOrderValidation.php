<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Validator;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface;

class PriceProductQuickOrderValidation implements PriceProductQuickOrderValidationInterface
{
    protected const ERROR_MESSAGE_NO_PRICE_PRODUCT = 'quick-order.upload-order.errors.upload-order-no-price-product';

    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface
     */
    protected $priceConcreteResolver;

    /**
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface $priceConcreteResolver
     */
    public function __construct(PriceConcreteResolverInterface $priceConcreteResolver)
    {
        $this->priceConcreteResolver = $priceConcreteResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        $productConcreteTransfer = $quickOrderItemTransfer->getProductConcrete();

        if (!$productConcreteTransfer || !$productConcreteTransfer->getIdProductConcrete()) {
            return $quickOrderItemTransfer;
        }

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($quickOrderItemTransfer->getQuantity())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());

        $priceProductTransfer = $this->priceConcreteResolver
            ->resolveCurrentProductPriceTransfer($priceProductFilterTransfer);

        if (!$priceProductTransfer->getPrice()) {
            $quickOrderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_NO_PRICE_PRODUCT);
        }

        return $quickOrderItemTransfer;
    }
}
