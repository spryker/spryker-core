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

        $this->assertEquals($expectedResult, $filteredResult[0]->getVolumeQuantity());
    }

    /**
     * @return array
     */
    public function filterShouldReturnPriceWithNearestVolumeQtyDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForFilterShouldReturnPriceWithNearestVolumeQty(1, 2, 3, 4, 3),
            'float stock' => $this->getDataForFilterShouldReturnPriceWithNearestVolumeQty(1.5, 2.5, 3.5, 3.1, 2.5),
            '0 < filterQty < 1' => $this->getDataForFilterShouldReturnPriceWithNearestVolumeQty(1.5, 2.5, null, 0.5, null),
        ];
    }

    /**
     * @param int|float $firstQty
     * @param int|float $secondQty
     * @param int|float $thirdQty
     * @param int|float $filterQty
     * @param int|float $expectedResult
     *
     * @return array
     */
    protected function getDataForFilterShouldReturnPriceWithNearestVolumeQty(
        $firstQty,
        $secondQty,
        $thirdQty,
        $filterQty,
        $expectedResult
    ): array {
        $priceProductFilterTransfer = (new PriceProductFilterBuilder())->seed([
                PriceProductFilterTransfer::QUANTITY => $filterQty,
            ])->build();
        $priceProductTransfers = [];
        $priceProductTransfers[] = (new PriceProductBuilder())->seed([
            PriceProductTransfer::VOLUME_QUANTITY => $firstQty,
        ])->build();
        $priceProductTransfers[] = (new PriceProductBuilder())->seed([
            PriceProductTransfer::VOLUME_QUANTITY => $secondQty,
        ])->build();
        $priceProductTransfers[] = (new PriceProductBuilder())->seed([
            PriceProductTransfer::VOLUME_QUANTITY => $thirdQty,
        ])->build();

        return [$priceProductFilterTransfer, $priceProductTransfers, $expectedResult];
    }
}
