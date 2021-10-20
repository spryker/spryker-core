<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Writer;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Spryker\Zed\MerchantProduct\Business\Exception\MerchantProductExistsException;
use Spryker\Zed\MerchantProduct\Persistence\MerchantProductEntityManagerInterface;
use Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface;

class MerchantProductWriter implements MerchantProductWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProduct\Persistence\MerchantProductEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface
     */
    protected $merchantProductRepository;

    /**
     * @param \Spryker\Zed\MerchantProduct\Persistence\MerchantProductEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface $merchantProductRepository
     */
    public function __construct(MerchantProductEntityManagerInterface $entityManager, MerchantProductRepositoryInterface $merchantProductRepository)
    {
        $this->entityManager = $entityManager;
        $this->merchantProductRepository = $merchantProductRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @throws \Spryker\Zed\MerchantProduct\Business\Exception\MerchantProductExistsException
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    public function create(MerchantProductTransfer $merchantProductTransfer): MerchantProductTransfer
    {
        $merchantProductTransfer->requireIdMerchant()->requireIdProductAbstract();

        $existingMerchantProduct = $this->merchantProductRepository->findMerchantProduct(
            (new MerchantProductCriteriaTransfer())->setIdProductAbstract($merchantProductTransfer->getIdProductAbstract()),
        );

        if ($existingMerchantProduct) {
            throw new MerchantProductExistsException();
        }

        return $this->entityManager->create($merchantProductTransfer);
    }
}
