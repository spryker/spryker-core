<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Model;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\MerchantRelationship\Business\Exception\MerchantRelationshipNotFoundException;
use Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpanderInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

class MerchantRelationshipReader implements MerchantRelationshipReaderInterface
{
    protected const ERROR_MESSAGE_UNBABLE_TO_DELETE_PRODUCT_LIST = 'Unable to delete Product List since it\'s used by Merchant Relation "%merchant_relation%".';
    protected const ERROR_MESSAGE_PARAM_MERCHANT_RELATION = '%merchant_relation%';

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpanderInterface
     */
    protected $merchantRelationshipExpander;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface $repository
     * @param \Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpanderInterface $merchantRelationshipExpander
     */
    public function __construct(
        MerchantRelationshipRepositoryInterface $repository,
        MerchantRelationshipExpanderInterface $merchantRelationshipExpander
    ) {
        $this->repository = $repository;
        $this->merchantRelationshipExpander = $merchantRelationshipExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @throws \Spryker\Zed\MerchantRelationship\Business\Exception\MerchantRelationshipNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->requireIdMerchantRelationship();

        $merchantRelationshipTransfer = $this->repository->getMerchantRelationshipById(
            $merchantRelationshipTransfer->getIdMerchantRelationship()
        );

        if (!$merchantRelationshipTransfer) {
            throw new MerchantRelationshipNotFoundException();
        }

        $merchantRelationshipTransfer = $this->merchantRelationshipExpander->expandWithName($merchantRelationshipTransfer);

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipByKey(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->requireMerchantRelationshipKey();

        $merchantRelationshipTransfer = $this->repository->findMerchantRelationshipByKey(
            $merchantRelationshipTransfer->getMerchantRelationshipKey()
        );

        if (!$merchantRelationshipTransfer) {
            return null;
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return int[]
     */
    public function getIdAssignedBusinessUnitsByMerchantRelationshipId(int $idMerchantRelationship): array
    {
        return $this->repository->getIdAssignedBusinessUnitsByMerchantRelationshipId($idMerchantRelationship);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer): array
    {
        $merchantRelationships = $this->repository->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        foreach ($merchantRelationships as $merchantRelationshipTransfer) {
             $this->merchantRelationshipExpander->expandWithName($merchantRelationshipTransfer);
        }

        return $merchantRelationships;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->requireIdMerchantRelationship();

        return $this->repository->getMerchantRelationshipById(
            $merchantRelationshipTransfer->getIdMerchantRelationship()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongMerchantRelationships(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        $productListTransfer->requireIdProductList();

        $productListResponseTransfer = (new ProductListResponseTransfer())
            ->setProductList($productListTransfer)
            ->setIsSuccessful(true);

        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())->setIdProductList(
            $productListTransfer->getIdProductList()
        );

        $merchantRelationshipTransfers = $this->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        if (!$merchantRelationshipTransfers) {
            return $productListResponseTransfer;
        }

        $productListResponseTransfer = $this->expandProductListResponseTransferWithMessages($productListResponseTransfer, $merchantRelationshipTransfers);

        return $productListResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer[] $merchantRelationshipTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    protected function expandMerchantRelationshipTransfersWithName(array $merchantRelationshipTransfers): array
    {
        foreach ($merchantRelationshipTransfers as $merchantRelationshipTransfer) {
            $merchantRelationshipTransfer = $this->merchantRelationshipExpander->expandWithName($merchantRelationshipTransfer);
        }

        return $merchantRelationshipTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer[] $merchantRelationshipTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListResponseTransferWithMessages(
        ProductListResponseTransfer $productListResponseTransfer,
        array $merchantRelationshipTransfers
    ): ProductListResponseTransfer {
        foreach ($merchantRelationshipTransfers as $merchantRelationshipTransfer) {
            $productListResponseTransfer->addMessage(
                (new MessageTransfer())->setValue(static::ERROR_MESSAGE_UNBABLE_TO_DELETE_PRODUCT_LIST)
                    ->setParameters([
                        static::ERROR_MESSAGE_PARAM_MERCHANT_RELATION => $merchantRelationshipTransfer->getName(),
                    ])
            );
        }

        return $productListResponseTransfer;
    }
}
