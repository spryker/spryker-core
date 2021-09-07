<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;

class SingleFieldPriceProductMapper implements SingleFieldPriceProductMapperInterface
{
    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_NET = 'net';
    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = 'gross';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     * @var string
     */
    protected const PRICE_DIMENSION_TYPE_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface[]
     */
    protected $fieldMapperStrategies = [];

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface[] $fieldMapperStrategies
     */
    public function __construct(array $fieldMapperStrategies)
    {
        $this->fieldMapperStrategies = $fieldMapperStrategies;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param array $data
     * @param int $volumeQuantity
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductTransfers(
        array $data,
        int $volumeQuantity,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        $dataField = (string)key($data);

        foreach ($this->fieldMapperStrategies as $fieldMapperStrategy) {
            if ($fieldMapperStrategy->isApplicable($dataField)) {
                return $fieldMapperStrategy
                    ->mapDataToPriceProductTransfers($data, $volumeQuantity, $priceProductTransfers);
            }
        }

        return $priceProductTransfers;
    }
}
