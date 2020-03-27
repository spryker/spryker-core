<?php


namespace Spryker\ProductLabelStorage\src\Spryker\Zed\ProductLabelStorage\Business\Mapper;


use Generated\Shared\Transfer\ProductLabelProductAbstractTransfer;

class ProductLabelProductAbstractMapper
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[] $productLabelProductAbstractTransferCollection
     *
     * @return int[]
     */
    public function mapProductLabelProductAbstractTransferCollectionToProductLabelIdsGroupedByProductAbstractIds(
        \ArrayObject $productLabelProductAbstractTransferCollection
    ): array
    {
        $groupedLabelsByProductAbstractId = [];

        foreach ($productLabelProductAbstractTransferCollection as $productLabelProductAbstractTransfer) {
            $groupedLabelsByProductAbstractId[$productLabelProductAbstractTransfer->getFkProductAbstract()][] =
                $productLabelProductAbstractTransfer->getFkProductLabel();
        }

        return $groupedLabelsByProductAbstractId;
    }
}
