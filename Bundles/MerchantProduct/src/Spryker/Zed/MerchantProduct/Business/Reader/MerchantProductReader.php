<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Reader;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface;
use Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface;

class MerchantProductReader implements MerchantProductReaderInterface
{
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
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer|null
     */
    public function findMerchantProduct(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?MerchantProductTransfer {
        $merchantProductTransfer = $this->merchantProductRepository->findMerchantProduct($merchantProductCriteriaTransfer);

        if (!$merchantProductTransfer || $merchantProductTransfer->getIdProductAbstract() === null) {
            return null;
        }

        $merchantProductTransfer->setProductAbstract(
            $this->productFacade->findProductAbstractById($merchantProductTransfer->getIdProductAbstract())
        );

        return $merchantProductTransfer;
    }
}
