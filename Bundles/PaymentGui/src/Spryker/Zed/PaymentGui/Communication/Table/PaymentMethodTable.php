<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui\Communication\Table;

use Orm\Zed\Payment\Persistence\Map\SpyPaymentMethodTableMap;
use Orm\Zed\Payment\Persistence\Map\SpyPaymentProviderTableMap;
use Orm\Zed\Payment\Persistence\SpyPaymentMethod;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class PaymentMethodTable extends AbstractTable
{
    protected const COL_ACTIONS = 'Actions';

    protected const PARAM_ID_PAYMENT_METHOD = 'id-payment-method';

    protected const URL_PAYMENT_METHOD_VIEW = '/payment-gui/view-payment-method/index';
    protected const URL_PAYMENT_METHOD_EDIT = '/payment-gui/update-payment-method/index';

    protected const BUTTON_VIEW = 'View';
    protected const BUTTON_EDIT = 'Edit';

    protected const HEADER_PAYMENT_METHOD_KEY = 'Payment Method Key';
    protected const HEADER_NAME = 'Name';
    protected const HEADER_PROVIDER = 'Provider';
    protected const HEADER_STATUS = 'Status';
    protected const HEADER_AVAILABLE_IN_STORE = 'Available in Store';
    protected const HEADER_ACTIONS = 'Actions';

    protected const LABEL_PRIMARY = 'label-primary';

    /**
     * @var \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery
     */
    protected $paymentMethodQuery;

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery $paymentMethodQuery
     */
    public function __construct(SpyPaymentMethodQuery $paymentMethodQuery)
    {
        $this->paymentMethodQuery = $paymentMethodQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->configureHeader($config);
        $config = $this->configureSortableColumns($config);
        $config = $this->configureSearchableColumns($config);
        $config = $this->setRawColumns($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configureHeader(TableConfiguration $tableConfiguration): TableConfiguration
    {
        $tableConfiguration->setHeader([
            SpyPaymentMethodTableMap::COL_PAYMENT_METHOD_KEY => static::HEADER_PAYMENT_METHOD_KEY,
            SpyPaymentMethodTableMap::COL_NAME => static::HEADER_NAME,
            SpyPaymentProviderTableMap::COL_NAME => static::HEADER_PROVIDER,
            SpyPaymentMethodTableMap::COL_IS_ACTIVE => static::HEADER_STATUS,
            SpyStoreTableMap::COL_NAME => static::HEADER_AVAILABLE_IN_STORE,
            static::COL_ACTIONS => static::HEADER_ACTIONS,
        ]);

        return $tableConfiguration;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configureSortableColumns(TableConfiguration $tableConfiguration): TableConfiguration
    {
        $tableConfiguration->setSortable([
            SpyPaymentMethodTableMap::COL_PAYMENT_METHOD_KEY,
            SpyPaymentMethodTableMap::COL_NAME,
            SpyPaymentProviderTableMap::COL_NAME,
            SpyPaymentMethodTableMap::COL_IS_ACTIVE,
        ]);

        return $tableConfiguration;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configureSearchableColumns(TableConfiguration $tableConfiguration): TableConfiguration
    {
        $tableConfiguration->setSearchable([
            SpyPaymentMethodTableMap::COL_NAME,
            SpyPaymentProviderTableMap::COL_NAME,
        ]);

        return $tableConfiguration;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setRawColumns(TableConfiguration $tableConfiguration): TableConfiguration
    {
        $tableConfiguration->setRawColumns([
            SpyPaymentMethodTableMap::COL_IS_ACTIVE,
            SpyStoreTableMap::COL_NAME,
            static::COL_ACTIONS,
        ]);

        return $tableConfiguration;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        /** @var \Orm\Zed\Payment\Persistence\SpyPaymentMethod[] $paymentMethodEntities */
        $paymentMethodEntities = $this->runQuery($this->prepareQuery(), $config, true);

        $paymentMethodRows = [];
        foreach ($paymentMethodEntities as $paymentMethodEntity) {
            $paymentMethodRows[] = $this->mapPaymentMethodRow($paymentMethodEntity);
        }

        return $paymentMethodRows;
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery
     */
    protected function prepareQuery(): SpyPaymentMethodQuery
    {
        return $this->paymentMethodQuery
            ->leftJoinWithSpyPaymentProvider();
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethod $paymentMethodEntity
     *
     * @return array
     */
    protected function mapPaymentMethodRow(SpyPaymentMethod $paymentMethodEntity): array
    {
        return [
            SpyPaymentMethodTableMap::COL_PAYMENT_METHOD_KEY => $paymentMethodEntity->getPaymentMethodKey(),
            SpyPaymentProviderTableMap::COL_NAME => $paymentMethodEntity->getSpyPaymentProvider()->getName(),
            SpyPaymentMethodTableMap::COL_NAME => $paymentMethodEntity->getName(),
            SpyPaymentMethodTableMap::COL_IS_ACTIVE => $this->generateIsActiveLabel($paymentMethodEntity),
            SpyStoreTableMap::COL_NAME => $this->getStoreNames($paymentMethodEntity),
            static::COL_ACTIONS => $this->buildLinks($paymentMethodEntity),
        ];
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethod $paymentMethodEntity
     *
     * @return string
     */
    protected function generateIsActiveLabel(SpyPaymentMethod $paymentMethodEntity): string
    {
        return $paymentMethodEntity->isActive() ? $this->generateLabel('Active', static::LABEL_PRIMARY)
            : $this->generateLabel('Inactive', 'label-light');
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethod $paymentMethodEntity
     *
     * @return string
     */
    protected function buildLinks(SpyPaymentMethod $paymentMethodEntity): string
    {
        return implode(' ', [
            $this->generatePaymentMethodViewButton($paymentMethodEntity),
            $this->generatePaymentMethodEditButton($paymentMethodEntity),
        ]);
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethod $paymentMethodEntity
     *
     * @return string
     */
    protected function generatePaymentMethodViewButton(SpyPaymentMethod $paymentMethodEntity): string
    {
        return $this->generateViewButton(
            Url::generate(static::URL_PAYMENT_METHOD_VIEW, [
                static::PARAM_ID_PAYMENT_METHOD => $paymentMethodEntity->getIdPaymentMethod(),
            ]),
            static::BUTTON_VIEW
        );
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethod $paymentMethodEntity
     *
     * @return string
     */
    protected function generatePaymentMethodEditButton(SpyPaymentMethod $paymentMethodEntity): string
    {
        return $this->generateEditButton(
            Url::generate(static::URL_PAYMENT_METHOD_EDIT, [
                static::PARAM_ID_PAYMENT_METHOD => $paymentMethodEntity->getIdPaymentMethod(),
            ]),
            static::BUTTON_EDIT
        );
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethod $paymentMethodEntity
     *
     * @return string
     */
    protected function getStoreNames(SpyPaymentMethod $paymentMethodEntity): string
    {
        $storeNames = [];
        foreach ($paymentMethodEntity->getSpyPaymentMethodStores() as $paymentMethodStore) {
            $storeName = $paymentMethodStore->getSpyStore()->getName();

            if ($storeName === null) {
                continue;
            }

            $storeNames[] = $this->generateLabel($storeName, static::LABEL_PRIMARY);
        }

        return implode(' ', $storeNames);
    }
}
