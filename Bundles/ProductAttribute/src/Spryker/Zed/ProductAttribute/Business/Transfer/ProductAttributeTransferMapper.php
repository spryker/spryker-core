<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Transfer;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;

class ProductAttributeTransferMapper implements ProductAttributeTransferMapperInterface
{

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface $localeFacade
     */
    public function __construct(ProductAttributeToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    public function convertProductAttribute(SpyProductAttribute $productImageEntity)
    {
        $productImageTransfer = (new ProductImageTransfer())
            ->fromArray($productImageEntity->toArray(), true);

        return $productImageTransfer;
    }


    public function convertProductAttributeCollection(ObjectCollection $productImageEntityCollection)
    {
        $transferList = [];
        foreach ($productImageEntityCollection as $productImageEntity) {
            $productImageTransfer = $this->convertProductAttribute($productImageEntity);
            $transferList[] = $productImageTransfer;
        }

        return $transferList;
    }

}
