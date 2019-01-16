<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class DiscontinuedAvailabilityQuickOrderValidation implements DiscontinuedAvailabilityQuickOrderValidationInterface
{
    protected const ERROR_MESSAGE_DISCONTINUED_PRODUCT = 'product_discontinued.error.message';

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
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        $productConcreteTransfer = $quickOrderItemTransfer->getProductConcrete();

        if (!$productConcreteTransfer) {
            return $quickOrderItemTransfer;
        }

        $productDiscontinuedTransfer = $this->productDiscontinuedStorageReader
            ->findProductDiscontinuedStorage($productConcreteTransfer->getSku(), $this->localeClient->getCurrentLocale());

        if ($productDiscontinuedTransfer) {
            $quickOrderItemTransfer->addErrorMessage((new MessageTransfer())->setValue(static::ERROR_MESSAGE_DISCONTINUED_PRODUCT));
        }

        return $quickOrderItemTransfer;
    }
}
