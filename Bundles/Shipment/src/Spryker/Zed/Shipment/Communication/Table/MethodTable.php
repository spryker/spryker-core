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

class MethodTable extends AbstractTable
{
    public const CARRIER = 'Carrier';
    public const METHOD = 'Method';
    public const DESCRIPTION = 'Description';
    public const GROSS_PRICE = 'Gross Price';
    public const NET_PRICE = 'Net Price';
    public const ACTIVE = 'Active';
    public const ACTIONS = 'Actions';
    public const PLUGINS = 'Plugins';

    public const AVAILABILITY_PLUGIN = 'Availability plugin';
    public const PRICE_PLUGIN = 'Price plugin';
    public const DELIVERY_TIME_PLUGIN = 'Delivery time plugin';

    public const ID_METHOD_PARAMETER = 'id-method';

    public const PRICE_TAG = '<span class="label label-info">%s</span>';

    /**
     * @var \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected $methodQuery;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery $methodQuery
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface $moneyFacade
     */
    public function __construct(SpyShipmentMethodQuery $methodQuery, ShipmentToMoneyInterface $moneyFacade)
    {
        $this->methodQuery = $methodQuery;
        $this->moneyFacade = $moneyFacade;
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
     * @uses MethodTable::getGrossMoneyTransfer
     * @uses MethodTable::getNetMoneyTransfer
     *
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param int $idShipmentMethod
     *
     * @return array
     */
    protected function getResult($method, $idShipmentMethod)
    {
        $methodPriceCollection = $method->getShipmentMethodPrices();
        $grossMoneyTransferCollection = array_map([$this, 'getGrossMoneyTransfer'], $methodPriceCollection->getArrayCopy());
        $netMoneyTransferCollection = array_map([$this, 'getNetMoneyTransfer'], $methodPriceCollection->getArrayCopy());

        return [
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => '<span class="label '
                . (($method->isActive()) ? 'label-success">Activated' : 'label-danger">Disabled') . '</span>',
            SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER => $method->getShipmentCarrier()->getName(),
            SpyShipmentMethodTableMap::COL_NAME => $method->getName(),
            SpyShipmentMethodPriceTableMap::COL_DEFAULT_GROSS_PRICE => $this->getPrices($grossMoneyTransferCollection),
            SpyShipmentMethodPriceTableMap::COL_DEFAULT_NET_PRICE => $this->getPrices($netMoneyTransferCollection),
            SpyShipmentMethodTableMap::COL_AVAILABILITY_PLUGIN => $method->getAvailabilityPlugin(),
            SpyShipmentMethodTableMap::COL_PRICE_PLUGIN => $method->getPricePlugin(),
            SpyShipmentMethodTableMap::COL_DELIVERY_TIME_PLUGIN => $method->getDeliveryTimePlugin(),

            self::ACTIONS => implode(' ', $this->createActionUrls($idShipmentMethod)),

        ];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice $methodPriceEntity
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getGrossMoneyTransfer(SpyShipmentMethodPrice $methodPriceEntity)
    {
        return (new MoneyTransfer())
            ->setAmount($methodPriceEntity->getDefaultGrossPrice())
            ->setCurrency((new CurrencyTransfer())->fromArray($methodPriceEntity->getCurrency()->toArray(), true));
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice $methodPriceEntity
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getNetMoneyTransfer(SpyShipmentMethodPrice $methodPriceEntity)
    {
        return (new MoneyTransfer())
            ->setAmount($methodPriceEntity->getDefaultNetPrice())
            ->setCurrency((new CurrencyTransfer())->fromArray($methodPriceEntity->getCurrency()->toArray(), true));
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
}
