<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Business\Expander\ProductOption;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupConditionsTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\MerchantProductOption\Dependency\Facade\MerchantProductOptionToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOption\Persistence\MerchantProductOptionRepositoryInterface;

class ProductOptionGroupExpander implements ProductOptionGroupExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOption\Persistence\MerchantProductOptionRepositoryInterface
     */
    protected $merchantProductOptionRepository;

    /**
     * @var \Spryker\Zed\MerchantProductOption\Dependency\Facade\MerchantProductOptionToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOption\Persistence\MerchantProductOptionRepositoryInterface $merchantProductOptionRepository
     * @param \Spryker\Zed\MerchantProductOption\Dependency\Facade\MerchantProductOptionToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantProductOptionRepositoryInterface $merchantProductOptionRepository,
        MerchantProductOptionToMerchantFacadeInterface $merchantFacade
    ) {
        $this->merchantProductOptionRepository = $merchantProductOptionRepository;
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function expandProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer): ProductOptionGroupTransfer
    {
        if ($productOptionGroupTransfer->getIdProductOptionGroup() === null) {
            return $productOptionGroupTransfer;
        }

        $merchantProductOptionGroupCriteriaTransfer = (new MerchantProductOptionGroupCriteriaTransfer())
            ->setMerchantProductOptionGroupConditions(
                (new MerchantProductOptionGroupConditionsTransfer())
                    ->addIdProductOptionGroup($productOptionGroupTransfer->getIdProductOptionGroup()),
            );

        $merchantProductOptionGroupCollectionTransfer = $this->merchantProductOptionRepository->getMerchantProductOptionGroupCollection(
            $merchantProductOptionGroupCriteriaTransfer,
        );

        if ($merchantProductOptionGroupCollectionTransfer->getMerchantProductOptionGroups()->count() === 0) {
            return $productOptionGroupTransfer;
        }

        $merchantProductOptionGroupTransfer = $merchantProductOptionGroupCollectionTransfer
            ->getMerchantProductOptionGroups()->getIterator()->current();
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantReference($merchantProductOptionGroupTransfer->getMerchantReference());

        $merchantTransfer = $this->merchantFacade->findOne($merchantCriteriaTransfer);

        if (!$merchantTransfer) {
            return $productOptionGroupTransfer;
        }

        return $productOptionGroupTransfer->setMerchant($merchantTransfer);
    }
}
