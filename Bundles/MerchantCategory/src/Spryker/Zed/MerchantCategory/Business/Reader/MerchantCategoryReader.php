<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCategoryResponseTransfer;
use Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface;

class MerchantCategoryReader implements MerchantCategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface $repository
     */
    public function __construct(MerchantCategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryResponseTransfer
     */
    public function get(MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer): MerchantCategoryResponseTransfer
    {
        $categoryTransfers = $this->repository->getCategories($merchantCategoryCriteriaTransfer);

        return (new MerchantCategoryResponseTransfer())
            ->setCategories(new ArrayObject($categoryTransfers))
            ->setIsSuccessful(true);
    }
}
