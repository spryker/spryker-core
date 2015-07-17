<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch;

use SprykerFeature\Zed\ProductSearch\Business\Operation\AddToResult;
use SprykerFeature\Zed\ProductSearch\Business\Operation\CopyToFacet;
use SprykerFeature\Zed\ProductSearch\Business\Operation\CopyToField;
use SprykerFeature\Zed\ProductSearch\Business\Operation\CopyToMultiField;
use SprykerFeature\Zed\ProductSearch\Business\Operation\OperationInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class ProductSearchConfig extends AbstractBundleConfig
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
