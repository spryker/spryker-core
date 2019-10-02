<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\Reader;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface;

class MerchantRelationshipReader implements MerchantRelationshipReaderInterface
{
    protected const ERROR_MESSAGE_UNBABLE_TO_DELETE_PRODUCT_LIST = 'Unable to delete Product List since it\'s used by Merchant Relation "%merchant_relation%".';
    protected const ERROR_MESSAGE_PARAM_MERCHANT_RELATION = '%merchant_relation%';

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
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongMerchantRelationships(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        $productListTransfer->requireIdProductList();

        $productListResponseTransfer = (new ProductListResponseTransfer())
            ->setProductList($productListTransfer)
            ->setIsSuccessful(true);

        $merchantRelationshipTransfers = $this->getMerchantRelationshipTransfers($productListTransfer);

        if (!$merchantRelationshipTransfers) {
            return $productListResponseTransfer;
        }

        $productListResponseTransfer = $this->expandProductListResponseTransferWithMessages($productListResponseTransfer, $merchantRelationshipTransfers);

        return $productListResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return int[]
     */
    public function getMerchantRelationshipIdsByProductList(ProductListTransfer $productListTransfer): array
    {
        $productListTransfer->requireIdProductList();

        $merchantRelationshipTransfers = $this->getMerchantRelationshipTransfers($productListTransfer);

        return array_map(function ($merchantRelationshipTransfer) {
            return $merchantRelationshipTransfer->getIdMerchantRelationship();
        }, $merchantRelationshipTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    protected function getMerchantRelationshipTransfers(ProductListTransfer $productListTransfer): array
    {
        $merchantRelationshipIds = $this->merchantRelationshipProductListRepository
            ->getMerchantRelationshipIdsByProductListId($productListTransfer->getIdProductList());

        if (!$merchantRelationshipIds) {
            return [];
        }

        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())->setMerchantRelationshipIds($merchantRelationshipIds);

        return $this->merchantRelationshipFacade->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);
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
