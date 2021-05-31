<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Business\Filter;

use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToMerchantProductOptionFacadeInterface;
use Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface;

class MerchantProductOptionFilter implements MerchantProductOptionFilterInterface
{
    /**
     * @uses \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig::STATUS_APPROVED
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface
     */
    protected $merchantProductOptionStorageRepository;

    /**
     * @var \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToMerchantProductOptionFacadeInterface
     */
    protected $merchantProductOptionFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface $merchantProductOptionStorageRepository
     * @param \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToMerchantProductOptionFacadeInterface $merchantProductOptionFacade
     */
    public function __construct(
        MerchantProductOptionStorageRepositoryInterface $merchantProductOptionStorageRepository,
        MerchantProductOptionStorageToMerchantProductOptionFacadeInterface $merchantProductOptionFacade
    ) {
        $this->merchantProductOptionStorageRepository = $merchantProductOptionStorageRepository;
        $this->merchantProductOptionFacade = $merchantProductOptionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    public function filterProductOptions(array $productOptionTransfers): array
    {
        $productOptionGroupIds = $this->extractProductOptionGroupIds($productOptionTransfers);
        $merchantProductOptionGroupCriteriaTransfer = (new MerchantProductOptionGroupCriteriaTransfer())
            ->setProductOptionGroupIds($productOptionGroupIds);
        $approvedMerchantProductOptionGroupCollectionTransfer = $this->merchantProductOptionFacade->getGroups($merchantProductOptionGroupCriteriaTransfer);
        $groupedProductOptionTransfers = $this->getProductOptionsGroupedByIdGroup($productOptionTransfers);

        foreach ($approvedMerchantProductOptionGroupCollectionTransfer->getMerchantProductOptionGroups() as $merchantProductOptionGroupTransfer) {
            if ($merchantProductOptionGroupTransfer->getApprovalStatus() === static::STATUS_APPROVED) {
                continue;
            }

            /** @var int $idProductOptionGroup */
            $idProductOptionGroup = $merchantProductOptionGroupTransfer->getFkProductOptionGroup();
            unset($groupedProductOptionTransfers[$idProductOptionGroup]);
        }

        return $groupedProductOptionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionTransfers
     *
     * @return int[]
     */
    protected function extractProductOptionGroupIds(array $productOptionTransfers): array
    {
        $productOptionGroupIds = [];

        foreach ($productOptionTransfers as $productOptionTransfer) {
            /** @var int $idGroup */
            $idGroup = $productOptionTransfer->getIdGroup();

            if (in_array($idGroup, $productOptionGroupIds, true)) {
                continue;
            }

            $productOptionGroupIds[] = $idGroup;
        }

        return $productOptionGroupIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function getProductOptionsGroupedByIdGroup(array $productOptionTransfers): array
    {
        $groupedProductOptionTransfers = [];

        foreach ($productOptionTransfers as $productOptionTransfer) {
            /** @var int $idGroup */
            $idGroup = $productOptionTransfer->getIdGroup();
            $groupedProductOptionTransfers[$idGroup] = $productOptionTransfer;
        }

        return $groupedProductOptionTransfers;
    }
}
