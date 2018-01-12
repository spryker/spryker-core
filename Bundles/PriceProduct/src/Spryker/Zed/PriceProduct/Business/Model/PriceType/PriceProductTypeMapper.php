<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\PriceType;

use Generated\Shared\Transfer\PriceTypeTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceType;

class PriceProductTypeMapper implements ProductPriceTypeMapperInterface
{
    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceType $priceTypeEntity
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    public function mapFromEntity(SpyPriceType $priceTypeEntity)
    {
        return (new PriceTypeTransfer())
          ->fromArray($priceTypeEntity->toArray(), true);
    }
}
