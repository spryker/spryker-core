<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Business\Label;

use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelFacadeInterface;

class LabelProvider implements LabelProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelFacadeInterface $productLabelFacade
     */
    public function __construct(ProductLabelDiscountConnectorToProductLabelFacadeInterface $productLabelFacade)
    {
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @return array<string, string>
     */
    public function findAllLabels(): array
    {
        $productLabelCollectionTransfer = $this->productLabelFacade->getProductLabelCollection(
            (new ProductLabelCriteriaTransfer())->addSort(
                (new SortTransfer())->setField(ProductLabelTransfer::POSITION)->setIsAscending(true),
            ),
        );

        return $this->getProductLabelNamesIndexedByProductLabelName($productLabelCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelCollectionTransfer $productLabelCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function getProductLabelNamesIndexedByProductLabelName(
        ProductLabelCollectionTransfer $productLabelCollectionTransfer
    ): array {
        $productLabelNames = [];
        foreach ($productLabelCollectionTransfer->getProductLabels() as $productLabelTransfer) {
            if (!$productLabelTransfer->getName()) {
                continue;
            }

            $productLabelNames[$productLabelTransfer->getNameOrFail()] = $productLabelTransfer->getNameOrFail();
        }

        return $productLabelNames;
    }
}
