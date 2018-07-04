<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Table;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodPriceTableMap;
use Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;

class MethodTable extends AbstractTable
{
    const CARRIER = 'Carrier';
    const METHOD = 'Method';
    const DESCRIPTION = 'Description';
    const GROSS_PRICE = 'Gross Price';
    const NET_PRICE = 'Net Price';
    const ACTIVE = 'Active';
    const ACTIONS = 'Actions';
    const PLUGINS = 'Plugins';

    const AVAILABILITY_PLUGIN = 'Availability plugin';
    const PRICE_PLUGIN = 'Price plugin';
    const DELIVERY_TIME_PLUGIN = 'Delivery time plugin';

    const ID_METHOD_PARAMETER = 'id-method';

    const PRICE_TAG = '<span class="label label-info">%s</span>';
    const STORE_TAG = '<div class="inline p-w-xs store-price" data-store-name="%s" data-store-id="%d">%s</div>';

    const GROUP_INDEX_FK_STORE = 'GROUP_INDEX_FK_STORE';
    const GROUP_INDEX_GROSS_MONEY_TRANSFER = 'GROUP_INDEX_GROSS_MONEY_TRANSFER';
    const GROUP_INDEX_NET_MONEY_TRANSFER = 'GROUP_INDEX_NET_MONEY_TRANSFER';

    /**
     * @var \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected $methodQuery;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    private $allStore;

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery $methodQuery
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     */
    public function __construct(
        SpyShipmentMethodQuery $methodQuery,
        ShipmentToMoneyInterface $moneyFacade,
        ShipmentToStoreInterface $storeFacade
    ) {
        $this->methodQuery = $methodQuery;
        $this->moneyFacade = $moneyFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->configureHeader($config);
        $this->configureSortable($config);
        $this->configureSearchable($config);
        $this->configureUrl($config);

        $config->addRawColumn(self::ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $this->allStore = $this->storeFacade->getAllStores();
        $query = $this->methodQuery;

        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $item) {
            $methodQuery = clone $query;
            $method = $methodQuery
                ->offset(0)
                ->findOneByIdShipmentMethod(
                    $item[SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD]
                );

            $results[] = $this->getResult($method, $item[SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD]);
        }

        return $results;
    }

    /**
     * @param int|null $value
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return string
     */
    protected function formatPrice($value, CurrencyTransfer $currencyTransfer)
    {
        if ($value === null) {
            return '';
        }

        $moneyTransfer = $this->moneyFacade
            ->fromInteger($value)
            ->setCurrency($currencyTransfer);

        return sprintf(static::PRICE_TAG, $this->moneyFacade->formatWithSymbol($moneyTransfer));
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureHeader(TableConfiguration $config)
    {
        $config->setHeader([
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => '',
            SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER => self::CARRIER,
            SpyShipmentMethodTableMap::COL_NAME => self::METHOD,
            SpyShipmentMethodPriceTableMap::COL_DEFAULT_GROSS_PRICE => static::GROSS_PRICE,
            SpyShipmentMethodPriceTableMap::COL_DEFAULT_NET_PRICE => static::NET_PRICE,
            SpyShipmentMethodTableMap::COL_AVAILABILITY_PLUGIN => self::AVAILABILITY_PLUGIN,
            SpyShipmentMethodTableMap::COL_PRICE_PLUGIN => self::PRICE_PLUGIN,
            SpyShipmentMethodTableMap::COL_DELIVERY_TIME_PLUGIN => self::DELIVERY_TIME_PLUGIN,

            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(SpyShipmentMethodTableMap::COL_IS_ACTIVE);
        $config->addRawColumn(SpyShipmentMethodPriceTableMap::COL_DEFAULT_NET_PRICE);
        $config->addRawColumn(SpyShipmentMethodPriceTableMap::COL_DEFAULT_GROSS_PRICE);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSortable(TableConfiguration $config)
    {
        $config->setSortable([
            SpyShipmentMethodTableMap::COL_DEFAULT_PRICE,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSearchable(TableConfiguration $config)
    {
        $config->setSearchable([
            SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER,
            SpyShipmentMethodTableMap::COL_NAME,
            SpyShipmentMethodTableMap::COL_DEFAULT_PRICE,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureUrl(TableConfiguration $config)
    {
        $config->setUrl('table');
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param int $idShipmentMethod
     *
     * @return array
     */
    protected function getResult($method, $idShipmentMethod)
    {
        $methodPriceCollection = $method->getShipmentMethodPrices();
        $groupedMoneyTransferCollections = array_map([$this, 'getPricesGroupedData'], $methodPriceCollection->getArrayCopy());

        return [
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => '<span class="label '
                . (($method->isActive()) ? 'label-success">Activated' : 'label-danger">Disabled') . '</span>',
            SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER => $method->getShipmentCarrier()->getName(),
            SpyShipmentMethodTableMap::COL_NAME => $method->getName(),
            SpyShipmentMethodPriceTableMap::COL_DEFAULT_GROSS_PRICE => $this->getGrossPrices($groupedMoneyTransferCollections),
            SpyShipmentMethodPriceTableMap::COL_DEFAULT_NET_PRICE => $this->getNetPrices($groupedMoneyTransferCollections),
            SpyShipmentMethodTableMap::COL_AVAILABILITY_PLUGIN => $method->getAvailabilityPlugin(),
            SpyShipmentMethodTableMap::COL_PRICE_PLUGIN => $method->getPricePlugin(),
            SpyShipmentMethodTableMap::COL_DELIVERY_TIME_PLUGIN => $method->getDeliveryTimePlugin(),

            self::ACTIONS => implode(' ', $this->createActionUrls($idShipmentMethod)),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer[] $moneyTransferCollection
     *
     * @return string
     */
    protected function getPrices(array $moneyTransferCollection)
    {
        $prices = [];
        foreach ($moneyTransferCollection as $moneyTransfer) {
            $prices[] = $this->formatPrice($moneyTransfer->getAmount(), $moneyTransfer->getCurrency());
        }

        return implode(' ', $prices);
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return array
     */
    protected function createActionUrls($idShipmentMethod)
    {
        $urls = [];
        $urls[] = $this->generateEditButton(
            Url::generate('/shipment/method/edit', [
                self::ID_METHOD_PARAMETER => $idShipmentMethod,
            ]),
            'Edit'
        );

        return $urls;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice $methodPriceEntity
     *
     * @return array
     */
    protected function getPricesGroupedData(SpyShipmentMethodPrice $methodPriceEntity)
    {
        return [
            self::GROUP_INDEX_FK_STORE => $methodPriceEntity->getFkStore(),
            self::GROUP_INDEX_GROSS_MONEY_TRANSFER => (new MoneyTransfer())
                ->setAmount($methodPriceEntity->getDefaultGrossPrice())
                ->setCurrency((new CurrencyTransfer())->fromArray($methodPriceEntity->getCurrency()->toArray(), true)),
            self::GROUP_INDEX_NET_MONEY_TRANSFER => (new MoneyTransfer())
                ->setAmount($methodPriceEntity->getDefaultNetPrice())
                ->setCurrency((new CurrencyTransfer())->fromArray($methodPriceEntity->getCurrency()->toArray(), true)),
        ];
    }

    /**
     * @param array $groupedMoneyTransferCollections
     *
     * @return string
     */
    private function getGrossPrices(array $groupedMoneyTransferCollections)
    {
        $priceType = self::GROUP_INDEX_GROSS_MONEY_TRANSFER;

        return $this->getPricesFromGrouped($groupedMoneyTransferCollections, $priceType);
    }

    /**
     * @param array $groupedMoneyTransferCollections
     *
     * @return string
     */
    private function getNetPrices(array $groupedMoneyTransferCollections)
    {
        $priceType = self::GROUP_INDEX_NET_MONEY_TRANSFER;

        return $result = $this->getPricesFromGrouped($groupedMoneyTransferCollections, $priceType);
    }

    /**
     * @param array $groupedMoneyTransferCollections
     * @param string $priceType
     *
     * @return string
     */
    protected function getPricesFromGrouped(array $groupedMoneyTransferCollections, string $priceType): string
    {
        $result = '';
        foreach ($this->allStore as $storeTransfer) {
            $moneyTransferCollections = array_filter($groupedMoneyTransferCollections, function ($groupedItem) use ($storeTransfer) {
                return $groupedItem[self::GROUP_INDEX_FK_STORE] == $storeTransfer->getIdStore();
            });

            $result .= sprintf(
                self::STORE_TAG,
                $storeTransfer->getName(),
                $storeTransfer->getIdStore(),
                $this->getPrices(array_column($moneyTransferCollections, $priceType)) ? : '-'
            );
        }
        return $result;
    }
}
