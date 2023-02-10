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
     * @var array<\Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface>
     */
    protected array $fieldMapperStrategies = [];

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface
     */
    protected PriceProductMapperInterface $priceProductMapper;

    /**
     * @param array<\Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface> $fieldMapperStrategies
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface $priceProductMapper
     */
    public function __construct(array $fieldMapperStrategies, PriceProductMapperInterface $priceProductMapper)
    {
        $this->fieldMapperStrategies = $fieldMapperStrategies;
        $this->priceProductMapper = $priceProductMapper;
    }

    /**
     * @param array<string, mixed> $data
     * @param int $volumeQuantity
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapPriceProductTransfers(
        array $data,
        int $volumeQuantity,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        $dataField = (string)key($data);

        $priceProductTransfers = $this->priceProductMapper->mapRequestDataToPriceProductTransfers(
            $data,
            $priceProductTransfers,
        );

        foreach ($this->fieldMapperStrategies as $fieldMapperStrategy) {
            if ($fieldMapperStrategy->isApplicable($dataField)) {
                return $fieldMapperStrategy
                    ->mapDataToPriceProductTransfers($data, $volumeQuantity, $priceProductTransfers);
            }
        }

        return new ArrayObject($priceProductTransfers);
    }
}
