<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Table;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;

class MethodTable extends AbstractTable
{

    const CARRIER = 'Carrier';
    const METHOD = 'Method';
    const DESCRIPTION = 'Description';
    const PRICE = 'Price';
    const ACTIVE = 'Active';
    const ACTIONS = 'Actions';
    const PLUGINS = 'Plugins';

    const AVAILABILITY_PLUGIN = 'Availability plugin';
    const PRICE_CALCULATION_PLUGIN = 'Price plugin';
    const DELIVERY_TIME_PLUGIN = 'Delivery time plugin';
    const TAX_CALCULATION_PLUGIN = 'Tax calculation plugin';

    const ID_METHOD_PARAMETER = 'id-method';

    /**
     * @var SpyShipmentMethodQuery
     */
    protected $methodQuery;

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery $methodQuery
     */
    public function __construct(SpyShipmentMethodQuery $methodQuery)
    {
        $this->methodQuery = $methodQuery;
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
     * @param int $value
     * @param bool $includeSymbol
     *
     * @return string
     */
    protected function formatPrice($value, $includeSymbol = true)
    {
        $currencyManager = CurrencyManager::getInstance();
        $value = $currencyManager->convertCentToDecimal($value);

        return $currencyManager->format($value, $includeSymbol);
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
            SpyShipmentMethodTableMap::COL_GLOSSARY_KEY_DESCRIPTION => self::DESCRIPTION,
            SpyShipmentMethodTableMap::COL_PRICE => self::PRICE,
            SpyShipmentMethodTableMap::COL_AVAILABILITY_PLUGIN => self::AVAILABILITY_PLUGIN,
            SpyShipmentMethodTableMap::COL_PRICE_CALCULATION_PLUGIN => self::PRICE_CALCULATION_PLUGIN,
            SpyShipmentMethodTableMap::COL_DELIVERY_TIME_PLUGIN => self::DELIVERY_TIME_PLUGIN,
            SpyShipmentMethodTableMap::COL_TAX_CALCULATION_PLUGIN => self::TAX_CALCULATION_PLUGIN,

            self::ACTIONS => self::ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSortable(TableConfiguration $config)
    {
        $config->setSortable([
            SpyShipmentMethodTableMap::COL_PRICE,
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
            SpyShipmentMethodTableMap::COL_GLOSSARY_KEY_DESCRIPTION,
            SpyShipmentMethodTableMap::COL_PRICE,
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
        return [
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => '<span class="label '
                . (($method->isActive()) ? 'label-success">Activated' : 'label-danger">Disabled') . '</span>',
            SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER => $method->getShipmentCarrier()->getName(),
            SpyShipmentMethodTableMap::COL_NAME => $method->getName(),
            SpyShipmentMethodTableMap::COL_GLOSSARY_KEY_DESCRIPTION => $method->getGlossaryKeyDescription(),
            SpyShipmentMethodTableMap::COL_PRICE => $this->formatPrice($method->getPrice()),
            SpyShipmentMethodTableMap::COL_AVAILABILITY_PLUGIN => $method->getAvailabilityPlugin(),
            SpyShipmentMethodTableMap::COL_PRICE_CALCULATION_PLUGIN => $method->getPriceCalculationPlugin(),
            SpyShipmentMethodTableMap::COL_DELIVERY_TIME_PLUGIN => $method->getDeliveryTimePlugin(),
            SpyShipmentMethodTableMap::COL_TAX_CALCULATION_PLUGIN => $method->getTaxCalculationPlugin(),

            self::ACTIONS => implode(' ', $this->createActionUrls($idShipmentMethod)),

        ];
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

        $urls[] = $this->generateRemoveButton(
            Url::generate('/shipment/method/delete', [
                self::ID_METHOD_PARAMETER => $idShipmentMethod,
            ]),
            'Delete'
        );

        return $urls;
    }

}
