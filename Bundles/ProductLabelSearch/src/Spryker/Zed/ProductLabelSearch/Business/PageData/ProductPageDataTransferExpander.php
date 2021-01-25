<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business\PageData;

use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
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
    public function expandProductPageDataTransferWithProductLabelIds(ProductPageLoadTransfer $productPageLoadTransfer)
    {
        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setProductAbstractIds($productPageLoadTransfer->getProductAbstractIds());
        $productLabelTransfers = $this->productLabelFacade
            ->getActiveLabelsByCriteria($productLabelCriteriaTransfer);

        $productLabelIdsMappedByIdProductAbstract = $this->productLabelMapper
            ->getProductLabelIdsMappedByIdProductAbstractAndStoreName($productLabelTransfers);

        $payloadTransfers = $this->expandPayloadTransfersWithProductLabelIds(
            $productPageLoadTransfer->getPayloadTransfers(),
            $productLabelIdsMappedByIdProductAbstract
        );

        return $productPageLoadTransfer->setPayloadTransfers($payloadTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param int[][] $productLabelIdsMappedByIdProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[]
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
                $productLabelIdsMappedByIdProductAbstract[$payloadTransfer->getIdProductAbstract()]
            );
        }

        return $payloadTransfers;
    }
}
