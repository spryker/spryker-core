<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderValidationResponseTransfer;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface;

class PriceProductQuickOrderValidator implements PriceProductQuickOrderValidatorInterface
{
    protected const ERROR_MESSAGE_NO_PRICE_PRODUCT = 'price_product.error.price_not_found';

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
     * @return \Generated\Shared\Transfer\QuickOrderValidationResponseTransfer
     */
    public function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderValidationResponseTransfer
    {
        $productConcreteTransfer = $quickOrderItemTransfer->getProductConcrete();
        $quickOrderValidationResponseTransfer = new QuickOrderValidationResponseTransfer();

        if (!$productConcreteTransfer || !$productConcreteTransfer->getIdProductConcrete()) {
            return $quickOrderValidationResponseTransfer;
        }

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($quickOrderItemTransfer->getQuantity())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());

        $priceProductTransfer = $this->priceConcreteResolver
            ->resolveCurrentProductPriceTransfer($priceProductFilterTransfer);

        if (!$priceProductTransfer->getPrice()) {
            $quickOrderValidationResponseTransfer->addErrorMessage((new MessageTransfer())
                ->setValue(static::ERROR_MESSAGE_NO_PRICE_PRODUCT));
        }

        return $quickOrderValidationResponseTransfer;
    }
}
