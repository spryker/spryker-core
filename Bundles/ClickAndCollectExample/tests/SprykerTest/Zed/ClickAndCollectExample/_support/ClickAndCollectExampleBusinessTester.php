<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ClickAndCollectExample;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery;
use Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ClickAndCollectExample\Business\ClickAndCollectExampleFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ClickAndCollectExample\PHPMD)
 */
class ClickAndCollectExampleBusinessTester extends Actor
{
    use _generated\ClickAndCollectExampleBusinessTesterActions;

    /**
     * @var string
     */
    public const TEST_SHIPMENT_TYPE_KEY_PICKUP = 'pickup1';

    /**
     * @var string
     */
    public const TEST_SHIPMENT_TYPE_KEY_DELIVERY = 'delivery1';

    /**
     * @var string
     */
    public const TEST_MERCHANT_REFERENCE_1 = 'merchant1';

    /**
     * @var string
     */
    public const TEST_MERCHANT_REFERENCE_2 = 'merchant2';

    /**
     * @var string
     */
    protected const TEST_ITEM_NAME = 'ItemA';

    /**
     * @var string
     */
    protected const TEST_ITEM_GROUP_KEY = 'GroupKey';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_EUR = 'EUR';

    /**
     * @uses \Spryker\Shared\ClickAndCollectExample\ClickAndCollectExampleConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function createItemTransfer(ProductConcreteTransfer $productConcreteTransfer): ItemTransfer
    {
        return (new ItemTransfer())
            ->setName(static::TEST_ITEM_NAME)
            ->setGroupKey(static::TEST_ITEM_GROUP_KEY)
            ->setSku($productConcreteTransfer->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(StoreTransfer $storeTransfer): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setStore($storeTransfer)
            ->setCurrency((new CurrencyTransfer())->setCode(static::CURRENCY_CODE_EUR))
            ->setPriceMode(static::PRICE_MODE_GROSS);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<string, mixed> $override
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createReplacementProductOffer(
        ProductConcreteTransfer $productConcreteTransfer,
        array $override = []
    ): ProductOfferTransfer {
        $productOfferTransfer = $this->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $override[ProductOfferTransfer::MERCHANT_REFERENCE] ?? null,
            ProductOfferTransfer::IS_ACTIVE => $override[ProductOfferTransfer::IS_ACTIVE] ?? true,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::STORES => $override[ProductOfferTransfer::STORES] ?? new ArrayObject(),
        ]);
        $this->havePriceProductOffer([
            PriceProductOfferTransfer::FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
        ]);

        $this->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferStockTransfer::QUANTITY => $override[ProductOfferStockTransfer::QUANTITY] ?? 0,
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => $override[ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK] ?? true,
        ]);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param array<string, mixed> $override
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createPickupReplacementProductOffer(
        ProductConcreteTransfer $productConcreteTransfer,
        ServiceTransfer $serviceTransfer,
        array $override = []
    ): ProductOfferTransfer {
        $productOfferTransfer = $this->createReplacementProductOffer($productConcreteTransfer, $override);
        $this->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdService(),
        ]);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param array<string, mixed> $override
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function createServiceTransfer(
        ServicePointTransfer $servicePointTransfer,
        array $override = []
    ): ServiceTransfer {
        $shipmentTypeTransfer = $this->haveShipmentType($override);
        $serviceTransfer = $this->haveService(
            array_merge([
                ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
                ServiceTransfer::IS_ACTIVE => true,
            ], $override),
        );

        $this->createShipmentTypeServiceType($shipmentTypeTransfer, $serviceTransfer->getServiceTypeOrFail());
        $shipmentTypeTransfer->setServiceType($serviceTransfer->getServiceTypeOrFail());

        return $serviceTransfer;
    }

    /**
     * @return void
     */
    public function mockClickAndCollectExampleConfig(): void
    {
        $clickAndCollectExampleConfigMock = Stub::make(
            ClickAndCollectExampleConfig::class,
            [
                'getPickupShipmentTypeKey' => static::TEST_SHIPMENT_TYPE_KEY_PICKUP,
                'getDeliveryShipmentTypeKey' => static::TEST_SHIPMENT_TYPE_KEY_DELIVERY,
            ],
        );

        $this->mockFactoryMethod('getConfig', $clickAndCollectExampleConfigMock);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return void
     */
    protected function createShipmentTypeServiceType(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        ServiceTypeTransfer $serviceTypeTransfer
    ): void {
        $this->getShipmentTypeServiceTypeQuery()
            ->filterByFkShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail())
            ->filterByFkServiceType($serviceTypeTransfer->getIdServiceTypeOrFail())
            ->findOneOrCreate()
            ->save();
    }

    /**
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery
     */
    protected function getShipmentTypeServiceTypeQuery(): SpyShipmentTypeServiceTypeQuery
    {
        return SpyShipmentTypeServiceTypeQuery::create();
    }
}
