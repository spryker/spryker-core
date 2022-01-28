<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\Validator;

use Generated\Shared\Transfer\MerchantRelationshipErrorTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Generated\Shared\Transfer\ProductListCollectionTransfer;
use Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface;

class MerchantRelationshipProductListValidator implements MerchantRelationshipProductListValidatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface
     */
    protected $merchantRelationshipProductListRepository;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface $merchantRelationshipProductListRepository
     */
    public function __construct(MerchantRelationshipProductListRepositoryInterface $merchantRelationshipProductListRepository)
    {
        $this->merchantRelationshipProductListRepository = $merchantRelationshipProductListRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer
     */
    public function validate(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
    ): MerchantRelationshipValidationErrorCollectionTransfer {
        $requestedProductListIds = $merchantRelationshipTransfer->getProductListIds();
        if (!$requestedProductListIds) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $availableProductListsForMerchantRelationship = $this->merchantRelationshipProductListRepository->getAvailableProductListsForMerchantRelationship(
            $merchantRelationshipTransfer,
        );

        if ($availableProductListsForMerchantRelationship->getProductLists()->count() === 0) {
            $merchantRelationshipValidationErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
                'assignedProductLists',
                'There are no available product lists',
            );

            return $merchantRelationshipValidationErrorCollectionTransfer->addError($merchantRelationshipValidationErrorTransfer);
        }

        $availableProductListIds = $this->extractProductListIdsFromProductListCollection($availableProductListsForMerchantRelationship);

        $unavailableProductListIds = array_diff($requestedProductListIds, $availableProductListIds);
        if (!$unavailableProductListIds) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        foreach ($unavailableProductListIds as $unavailableIdProductList) {
            $merchantRelationshipValidationErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
                'assignedProductLists',
                sprintf('Product list can not be assigned by id `%s`.', $unavailableIdProductList),
            );

            $merchantRelationshipValidationErrorCollectionTransfer->addError($merchantRelationshipValidationErrorTransfer);
        }

        return $merchantRelationshipValidationErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListCollectionTransfer $productListCollectionTransfer
     *
     * @return array<int>
     */
    protected function extractProductListIdsFromProductListCollection(ProductListCollectionTransfer $productListCollectionTransfer): array
    {
        $productListIds = [];
        foreach ($productListCollectionTransfer->getProductLists() as $productListTransfer) {
            $productListIds[] = $productListTransfer->getIdProductListOrFail();
        }

        return $productListIds;
    }

    /**
     * @param string $field
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipErrorTransfer
     */
    protected function createMerchantRelationshipErrorTransfer(string $field, string $message): MerchantRelationshipErrorTransfer
    {
        return (new MerchantRelationshipErrorTransfer())
            ->setField($field)
            ->setMessage($message);
    }
}
