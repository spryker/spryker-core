<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Validator;

use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class DiscontinuedAvailabilityQuickOrderValidation implements DiscontinuedAvailabilityQuickOrderValidationInterface
{
    protected const ERROR_MESSAGE_DISCONTINUED_PRODUCT = 'quick-order.upload-order.errors.upload-order-discontinued-product';

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
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function validateQuickOrder(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        foreach ($quickOrderTransfer->getItems() as $orderItemTransfer) {
            $productConcreteTransfer = $orderItemTransfer->getProductConcrete();

            if (!$productConcreteTransfer) {
                continue;
            }

            $productDiscontinuedTransfer = $this->productDiscontinuedStorageReader
                ->findProductDiscontinuedStorage($productConcreteTransfer->getSku(), $this->localeClient->getCurrentLocale());

            if ($productDiscontinuedTransfer) {
                $orderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_DISCONTINUED_PRODUCT);
            }
        }

        return $quickOrderTransfer;
    }
}
