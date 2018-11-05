<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProduct;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProduct
 * @group PriceProductServiceTest
 * Add your own group annotations below this line
 */
class PriceProductServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\PriceProduct\PriceProductTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMergeConcreteAndAbstractPrices(): void
    {
        $priceProductService = $this->getPriceProductService();

        $concretePriceProductTransfers = $this->getPriceProductTransfers();
        $abstractPriceProductTransfers = $this->getPriceProductTransfers();

        $priceProductService->mergeConcreteAndAbstractPrices($concretePriceProductTransfers, $abstractPriceProductTransfers);
    }

    /**
     * @return \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected function getPriceProductService(): PriceProductServiceInterface
    {
        return $this->tester->getLocator()->priceProduct()->service();
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    protected function getPriceProductTransfers(): array
    {
        return [
            (new PriceProductBuilder(['priceTypeName' => 'DEFAULT']))
                ->withMoneyValue((new MoneyValueBuilder())->withCurrency())
                ->withPriceDimension()
                ->withPriceType()
                ->build(),
            (new PriceProductBuilder(['priceTypeName' => 'ORIGINAL']))
                ->withMoneyValue((new MoneyValueBuilder())->withCurrency())
                ->withPriceDimension()
                ->withPriceType()
                ->build(),
        ];
    }
}
