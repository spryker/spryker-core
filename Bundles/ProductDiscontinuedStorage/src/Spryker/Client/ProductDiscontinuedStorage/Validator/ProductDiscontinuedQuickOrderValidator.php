<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderValidationResponseTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class ProductDiscontinuedQuickOrderValidator implements ProductDiscontinuedQuickOrderValidatorInterface
{
    protected const ERROR_MESSAGE_DISCONTINUED_PRODUCT = 'product_discontinued.message.product_discontinued';

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface
     */
    protected $productDiscontinuedStorageReader;

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader
     * @param \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface $localeClient
     */
    public function __construct(ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader, ProductDiscontinuedStorageToLocaleClientInterface $localeClient)
    {
        $this->productDiscontinuedStorageReader = $productDiscontinuedStorageReader;
        $this->localeClient = $localeClient;
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

        if (!$productConcreteTransfer) {
            return $quickOrderValidationResponseTransfer;
        }

        $productDiscontinuedTransfer = $this->productDiscontinuedStorageReader
            ->findProductDiscontinuedStorage($productConcreteTransfer->getSku(), $this->localeClient->getCurrentLocale());

        if ($productDiscontinuedTransfer) {
            $quickOrderValidationResponseTransfer->addErrorMessage((new MessageTransfer())
                ->setValue(static::ERROR_MESSAGE_DISCONTINUED_PRODUCT));
        }

        return $quickOrderValidationResponseTransfer;
    }
}
