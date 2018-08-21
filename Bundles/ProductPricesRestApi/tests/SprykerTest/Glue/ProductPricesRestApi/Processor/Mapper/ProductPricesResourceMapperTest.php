<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductPricesRestApi\Processor\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapper;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapperInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapper;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapperInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group ProductPricesRestApi
 * @group Processor
 * @group Mapper
 * @group ProductPricesResourceMapperTest
 * Add your own group annotations below this line
 */
class ProductPricesResourceMapperTest extends Unit
{
    protected const KEY_CURRENCY_EUR = 'EUR';
    protected const KEY_CURRENCY_CHF = 'CHF';

    /**
     * @return void
     */
    public function testMapConcreteProductPricesTransferToRestResource(): void
    {
        $mapper = $this->getConcreteProductPricesResourceMapper();
        $dataTransfer = $this->getPriceProductStorageTransfer();
        $idResource = (string)random_int(1, 100);

        $restResource = $mapper->mapConcreteProductPricesTransferToRestResource($dataTransfer, $idResource);

        /** @var \Generated\Shared\Transfer\RestProductPricesAttributesTransfer $attributes */
        $attributes = $restResource->getAttributes();
        $prices = $attributes->getPrices();

        $this->assertInstanceOf(RestProductPricesAttributesTransfer::class, $attributes);
        $this->assertSame($idResource, $restResource->getId());
        $this->assertInternalType('array', $prices);
        $this->assertArrayHasKey(static::KEY_CURRENCY_EUR, $prices);
        $this->assertArrayHasKey(static::KEY_CURRENCY_CHF, $prices);
    }

    /**
     * @return void
     */
    public function testMapAbstractProductPricesTransferToRestResource(): void
    {
        $mapper = $this->getAbstractProductPricesResourceMapper();
        $dataTransfer = $this->getPriceProductStorageTransfer();
        $idResource = (string)random_int(1, 100);

        $restResource = $mapper->mapAbstractProductPricesTransferToRestResource($dataTransfer, $idResource);

        /** @var \Generated\Shared\Transfer\RestProductPricesAttributesTransfer $attributes */
        $attributes = $restResource->getAttributes();
        $prices = $attributes->getPrices();

        $this->assertInstanceOf(RestProductPricesAttributesTransfer::class, $attributes);
        $this->assertSame($idResource, $restResource->getId());
        $this->assertInternalType('array', $prices);
        $this->assertArrayHasKey(static::KEY_CURRENCY_EUR, $prices);
        $this->assertArrayHasKey(static::KEY_CURRENCY_CHF, $prices);
    }

    /**
     * @return \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapperInterface
     */
    protected function getAbstractProductPricesResourceMapper(): AbstractProductPricesResourceMapperInterface
    {
        return new AbstractProductPricesResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapperInterface
     */
    protected function getConcreteProductPricesResourceMapper(): ConcreteProductPricesResourceMapperInterface
    {
        return new ConcreteProductPricesResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected function getResourceBuilder(): RestResourceBuilderInterface
    {
        return $this->getMockBuilder(RestResourceBuilder::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer
     */
    protected function getPriceProductStorageTransfer(): PriceProductStorageTransfer
    {
        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $priceProductStorageTransfer->setPrices([
            static::KEY_CURRENCY_EUR => [
                'priceData' => null,
                'GROSS_MODE' => [
                    'DEFAULT' => 9999,
                    'ORIGINAL' => 12564,
                ],
                'NET_MODE' => [
                    'DEFAULT' => 8999,
                    'ORIGINAL' => 11308,
                ],
            ],
            static::KEY_CURRENCY_CHF => [
                'priceData' => null,
                'GROSS_MODE' => [
                    'DEFAULT' => 11499,
                    'ORIGINAL' => 14449,
                ],
                'NET_MODE' => [
                    'DEFAULT' => 10349,
                    'ORIGINAL' => 13004,
                ],
            ],
        ]);

        return $priceProductStorageTransfer;
    }
}
