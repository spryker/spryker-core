<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductVolume;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\DataBuilder\PriceProductFilterBuilder;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProductVolume\Plugin\PriceProductExtension\PriceProductVolumeFilterPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group PriceProductVolume
 * @group PriceProductVolumeFilterPluginTest
 * Add your own group annotations below this line
 */
class PriceProductVolumeFilterPluginTest extends Unit
{
    /**
     * @dataProvider filterShouldReturnPriceWithNearestVolumeQtyDataProvider
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param int|float $expectedResult
     *
     * @return void
     */
    public function testFilterShouldReturnPriceWithNearestVolumeQty(
        PriceProductFilterTransfer $priceProductFilterTransfer,
        array $priceProductTransfers,
        $expectedResult
    ): void {
        $plugin = new PriceProductVolumeFilterPlugin();

        $filteredResult = $plugin->filter($priceProductTransfers, $priceProductFilterTransfer);
        $filteredResult = array_values($filteredResult);

        $this->assertEquals($expectedResult, $filteredResult[0]->getVolumeQuantity(), 'Volume quantity does not match to the expected one');
    }

    /**
     * @return array
     */
    public function filterShouldReturnPriceWithNearestVolumeQtyDataProvider(): array
    {
        return [
            'int stock return 3rd volume' => $this->getDataForFilterShouldReturnPriceWithNearestVolumeQty(1, 2, 3, 4, 3),
            'float stock return 2nd volume' => $this->getDataForFilterShouldReturnPriceWithNearestVolumeQty(1.5, 2.5, 3.5, 3.1, 2.5),
            '0 < filterQty < 1 return price with no volume' => $this->getDataForFilterShouldReturnPriceWithNearestVolumeQty(1.5, 2.5, null, 0.5, null),
            'selected quantity is 1, closest volume price less then 1' => $this->getDataForFilterShouldReturnPriceWithNearestVolumeQty(0.5, 2.5, null, 1, 0.5),
        ];
    }

    /**
     * @param int|float $firstVolumeQuantity
     * @param int|float $secondVolumeQuantity
     * @param int|float $thirdVolumeQuantity
     * @param int|float $quantityToBuy
     * @param int|float $expectedResult
     *
     * @return array
     */
    protected function getDataForFilterShouldReturnPriceWithNearestVolumeQty(
        $firstVolumeQuantity,
        $secondVolumeQuantity,
        $thirdVolumeQuantity,
        $quantityToBuy,
        $expectedResult
    ): array {
        $priceProductFilterTransfer = (new PriceProductFilterBuilder())->seed([
                PriceProductFilterTransfer::QUANTITY => $quantityToBuy,
            ])->build();
        $priceProductTransfers = [];
        $priceProductTransfers[] = (new PriceProductBuilder())->seed([
            PriceProductTransfer::VOLUME_QUANTITY => $firstVolumeQuantity,
        ])->build();
        $priceProductTransfers[] = (new PriceProductBuilder())->seed([
            PriceProductTransfer::VOLUME_QUANTITY => $secondVolumeQuantity,
        ])->build();
        $priceProductTransfers[] = (new PriceProductBuilder())->seed([
            PriceProductTransfer::VOLUME_QUANTITY => $thirdVolumeQuantity,
        ])->build();

        return [$priceProductFilterTransfer, $priceProductTransfers, $expectedResult];
    }
}
