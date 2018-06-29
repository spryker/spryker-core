<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Plugin\PriceProductExtension;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface;

/**
 * @method \Spryker\Service\PriceProduct\PriceProductConfig getConfig()
 */
class DefaultPriceProductFilterPlugin extends AbstractPlugin implements PriceProductFilterPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
//        $resultPriceProductTransfers = [];
//        $minPriceProductTransfer = null;
//
//        foreach ($priceProductTransfers as $priceProductTransfer) {
//            $priceProductTransfer->requirePriceDimension();
//
//            if (!$priceProductTransfer->getPriceDimension()->getIdPriceProductDefault()) {
//                $resultPriceProductTransfers[] = $priceProductTransfer;
//            }
//
//            if ($minPriceProductTransfer === null || $minPriceProductTransfer->get) {
//                $minPriceProductTransfer = $priceProductTransfer->get;
//            }
//        }
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return $this->getConfig()->getPriceDimensionDefault();
    }
}
