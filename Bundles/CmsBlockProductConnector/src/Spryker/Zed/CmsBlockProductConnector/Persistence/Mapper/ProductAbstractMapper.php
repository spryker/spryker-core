<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence\Mapper;

use Propel\Runtime\Collection\Collection;

class ProductAbstractMapper implements ProductAbstractMapperInterface
{
    public const PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME = 'name';

    /**
     * @param \Propel\Runtime\Collection\Collection|null $productAbstracts
     *
     * @return array
     */
    public function mapProductAbstractArrayToOptions(?Collection $productAbstracts): array
    {
        $productAbstractOptions = [];

        if (!$productAbstracts) {
            return [];
        }

        foreach ($productAbstracts as $spyProductAbstract) {
            $label = $spyProductAbstract->getVirtualColumn(static::PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME) .
                ' (SKU: ' . $spyProductAbstract->getSku() . ')';

            $productAbstractOptions[$label] = $spyProductAbstract->getIdProductAbstract();
        }

        return $productAbstractOptions;
    }
}
