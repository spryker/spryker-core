<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Updater;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductAbstractResponseTransfer;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface;
use Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface;

class ProductAbstractUpdater implements ProductAbstractUpdaterInterface
{
    protected const MESSAGE_MERCHANT_PRODUCT_NOT_FOUND = 'Merchant product is not found for product abstract id %d and merchant id %d.';

    /**
     * @var \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface
     */
    protected $merchantProductRepository;

    /**
     * @var \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface $merchantProductRepository
     * @param \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface $productFacade
     */
    public function __construct(
        MerchantProductRepositoryInterface $merchantProductRepository,
        MerchantProductToProductFacadeInterface $productFacade
    ) {
        $this->merchantProductRepository = $merchantProductRepository;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractResponseTransfer
     */
    public function updateProductAbstract(MerchantProductTransfer $merchantProductTransfer): ProductAbstractResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = $merchantProductTransfer->requireProductAbstract()->getProductAbstract();

        /** @var int $idMerchant */
        $idMerchant = $merchantProductTransfer->requireIdMerchant()->getIdMerchant();
        /** @var int $idProductAbstract */
        $idProductAbstract = $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract();

        $productAbstractResponseTransfer = (new ProductAbstractResponseTransfer())
            ->setIsSuccessful(true)
            ->setProductAbstract($productAbstractTransfer);

        $merchantProductCriteriaTransfer = (new MerchantProductCriteriaTransfer())
            ->addIdMerchant($idMerchant)
            ->setIdProductAbstract($idProductAbstract);

        $storedMerchantProductTransfer = $this->merchantProductRepository->findMerchantProduct($merchantProductCriteriaTransfer);

        if (!$storedMerchantProductTransfer) {
            $productAbstractResponseTransfer->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(sprintf(
                        static::MESSAGE_MERCHANT_PRODUCT_NOT_FOUND,
                        $idProductAbstract,
                        $idMerchant
                    ))
                );

            return $productAbstractResponseTransfer;
        }

        $this->productFacade->saveProductAbstract($productAbstractTransfer);

        return $productAbstractResponseTransfer;
    }
}
