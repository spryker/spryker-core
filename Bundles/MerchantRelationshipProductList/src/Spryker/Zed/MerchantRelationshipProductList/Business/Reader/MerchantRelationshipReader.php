<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\Reader;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface;

class MerchantRelationshipReader implements MerchantRelationshipReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface
     */
    protected $merchantRelationshipProductListRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface $merchantRelationshipProductListRepository
     * @param \Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(
        MerchantRelationshipProductListRepositoryInterface $merchantRelationshipProductListRepository,
        MerchantRelationshipProductListToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
    ) {
        $this->merchantRelationshipProductListRepository = $merchantRelationshipProductListRepository;
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    public function getMerchantRelationshipsByProductList(ProductListTransfer $productListTransfer): array
    {
        $productListTransfer->requireIdProductList();

        $merchantRelationshipIds = $this->merchantRelationshipProductListRepository
            ->getMerchantRelationshipIdsByProductListId($productListTransfer->getIdProductList());

        if (!$merchantRelationshipIds) {
            return [];
        }

        $merchantRelationshipCriteriaTransfer = $this->createMerchantRelationshipCriteriaTransfer($merchantRelationshipIds);
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        if (!$merchantRelationshipCollectionTransfer instanceof MerchantRelationshipCollectionTransfer) {
            return $merchantRelationshipCollectionTransfer;
        }

        if ($merchantRelationshipCollectionTransfer->getMerchantRelationships()->count() === 0) {
            return [];
        }

        return $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getArrayCopy();
    }

    /**
     * @param array<int> $merchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function createMerchantRelationshipCriteriaTransfer(array $merchantRelationshipIds): MerchantRelationshipCriteriaTransfer
    {
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->setMerchantRelationshipIds($merchantRelationshipIds);

        return (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);
    }
}
