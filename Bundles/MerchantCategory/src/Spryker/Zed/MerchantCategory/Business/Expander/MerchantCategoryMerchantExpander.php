<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface;

class MerchantCategoryMerchantExpander implements MerchantCategoryMerchantExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface
     */
    protected MerchantCategoryRepositoryInterface $merchantCategoryRepository;

    /**
     * @param \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface $merchantCategoryRepository
     */
    public function __construct(
        MerchantCategoryRepositoryInterface $merchantCategoryRepository
    ) {
        $this->merchantCategoryRepository = $merchantCategoryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expand(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        if ($merchantCollectionTransfer->getMerchants()->count() === 0) {
            return $merchantCollectionTransfer;
        }

        $merchantCategoryCriteriaTransfer = $this->createMerchantCategoryCriteria($merchantCollectionTransfer);
        $merchantCategoryTransfers = $this->merchantCategoryRepository->get($merchantCategoryCriteriaTransfer);

        if (count($merchantCategoryTransfers) === 0) {
            return $merchantCollectionTransfer;
        }

        $categoryTransfersGroupedByIdMerchant = $this->getCategoryTransfersGroupedByIdMerchant($merchantCategoryTransfers);

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $idMerchant = $merchantTransfer->getIdMerchantOrFail();

            if (
                !isset($categoryTransfersGroupedByIdMerchant[$idMerchant])
                || $categoryTransfersGroupedByIdMerchant[$idMerchant]->count() === 0
            ) {
                continue;
            }

            $merchantTransfer->setCategories($categoryTransfersGroupedByIdMerchant[$idMerchant]);
        }

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer
     */
    protected function createMerchantCategoryCriteria(
        MerchantCollectionTransfer $merchantCollectionTransfer
    ): MerchantCategoryCriteriaTransfer {
        $merchantIds = [];

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantIds[] = $merchantTransfer->getIdMerchantOrFail();
        }

        return (new MerchantCategoryCriteriaTransfer())
            ->setMerchantIds($merchantIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\MerchantCategoryTransfer> $merchantCategoryTransfers
     *
     * @return array<int, \ArrayObject<int, \Generated\Shared\Transfer\CategoryTransfer>>
     */
    protected function getCategoryTransfersGroupedByIdMerchant(array $merchantCategoryTransfers): array
    {
        $categoryTransfersGroupedByIdMerchant = [];

        foreach ($merchantCategoryTransfers as $merchantCategoryTransfer) {
            $fkMerchant = $merchantCategoryTransfer->getFkMerchantOrFail();

            if (!isset($categoryTransfersGroupedByIdMerchant[$fkMerchant])) {
                $categoryTransfersGroupedByIdMerchant[$fkMerchant] = new ArrayObject();
            }

            $categoryTransfersGroupedByIdMerchant[$fkMerchant]->append($merchantCategoryTransfer->getCategory());
        }

        return $categoryTransfersGroupedByIdMerchant;
    }
}
