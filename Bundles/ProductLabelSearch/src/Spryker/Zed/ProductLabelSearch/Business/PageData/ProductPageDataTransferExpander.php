<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business\PageData;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelConditionsTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\ProductLabelSearch\Business\Mapper\ProductLabelMapperInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface;

class ProductPageDataTransferExpander implements ProductPageDataTransferExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelSearch\Business\Mapper\ProductLabelMapperInterface
     */
    protected $productLabelMapper;

    /**
     * @var \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabelSearch\Business\Mapper\ProductLabelMapperInterface $productLabelMapper
     * @param \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface $productLabelFacade
     */
    public function __construct(
        ProductLabelMapperInterface $productLabelMapper,
        ProductLabelSearchToProductLabelInterface $productLabelFacade
    ) {
        $this->productLabelMapper = $productLabelMapper;
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransferWithProductLabelIds(ProductPageLoadTransfer $productPageLoadTransfer): ProductPageLoadTransfer
    {
        $productLabelCollectionTransfer = $this->getProductLabelCollection($productPageLoadTransfer);

        if (!$productLabelCollectionTransfer->getProductLabels()->count()) {
            return $productPageLoadTransfer;
        }

        $productLabelIdsMappedByIdProductAbstract = $this->productLabelMapper
            ->getProductLabelIdsMappedByIdProductAbstractAndStoreName($productLabelCollectionTransfer);

        $payloadTransfers = $this->expandPayloadTransfersWithProductLabelIds(
            $productPageLoadTransfer->getPayloadTransfers(),
            $productLabelIdsMappedByIdProductAbstract,
        );

        return $productPageLoadTransfer->setPayloadTransfers($payloadTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductPayloadTransfer> $payloadTransfers
     * @param array<array<int>> $productLabelIdsMappedByIdProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductPayloadTransfer>
     */
    protected function expandPayloadTransfersWithProductLabelIds(
        array $payloadTransfers,
        array $productLabelIdsMappedByIdProductAbstract
    ): array {
        foreach ($payloadTransfers as $payloadTransfer) {
            if (!isset($productLabelIdsMappedByIdProductAbstract[$payloadTransfer->getIdProductAbstract()])) {
                continue;
            }

            $payloadTransfer->setLabelIds(
                $productLabelIdsMappedByIdProductAbstract[$payloadTransfer->getIdProductAbstract()],
            );
        }

        return $payloadTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelCollectionTransfer
     */
    protected function getProductLabelCollection(ProductPageLoadTransfer $productPageLoadTransfer): ProductLabelCollectionTransfer
    {
        $productAbstractIds = array_filter($productPageLoadTransfer->getProductAbstractIds());
        if (!$productAbstractIds) {
            return new ProductLabelCollectionTransfer();
        }

        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setProductLabelConditions(
                (new ProductLabelConditionsTransfer())
                    ->setIsActive(true)
                    ->setProductAbstractIds($productAbstractIds),
            )
            ->setSortCollection(new ArrayObject([
                (new SortTransfer())->setField(ProductLabelTransfer::IS_EXCLUSIVE)->setIsAscending(false),
                (new SortTransfer())->setField(ProductLabelTransfer::POSITION)->setIsAscending(true),
            ]))
            ->setWithProductLabelStores(true)
            ->setWithProductLabelProductAbstracts(true);

        return $this->productLabelFacade->getProductLabelCollection($productLabelCriteriaTransfer);
    }
}
