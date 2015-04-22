<?php

namespace SprykerFeature\Zed\ProductSearch\Business;


use SprykerFeature\Zed\ProductSearch\Business\Operation\AddToResult;
use SprykerFeature\Zed\ProductSearch\Business\Operation\CopyToFacet;
use SprykerFeature\Zed\ProductSearch\Business\Operation\CopyToField;
use SprykerFeature\Zed\ProductSearch\Business\Operation\CopyToMultiField;
use SprykerFeature\Zed\ProductSearch\Business\Operation\OperationInterface;

/**
 * Class ProductSearchSettings
 *
 * @package SprykerFeature\Zed\ProductSearch\Business
 */
class ProductSearchSettings
{
    /**
     * @return array|OperationInterface[]
     */
    public function getPossibleOperations()
    {
        return [
            new AddToResult(),
            new CopyToField(),
            new CopyToFacet(),
            new CopyToMultiField(),
        ];
    }
}
