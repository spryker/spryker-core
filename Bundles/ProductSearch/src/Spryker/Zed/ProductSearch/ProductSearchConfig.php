<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch;

use Spryker\Zed\ProductSearch\Business\Operation\AddToResult;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToFacet;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToField;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToMultiField;
use Spryker\Zed\ProductSearch\Business\Operation\OperationInterface;
use Spryker\Zed\Kernel\AbstractBundleConfig;

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
