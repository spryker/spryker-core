<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Table;

use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderItemTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTotalsTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMoneyFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilSanitizeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiConfig;

class MerchantOrderTable extends AbstractTable
{
    protected const COL_FULL_CUSTOMER_NAME = 'fullCustomerName';
    protected const COL_ITEM_COUNT = 'itemCount';
    protected const COL_ORDER_STATE = 'orderState';
    protected const COL_ACTIONS = 'actions';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller\DetailController::ROUTE_REDIRECT
     */
    protected const ROUTE_REDIRECT = '/merchant-sales-order-merchant-user-gui/detail';

    /**
     * @phpstan-var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed>
     *
     * @var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected $merchantSalesOrderQuery;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilSanitizeInterface
     */
    protected $sanitizeService;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @phpstan-param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed> $merchantSalesOrderQuery
     *
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilSanitizeInterface $sanitizeService
     * @param \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantSalesOrderMerchantUserGuiToMoneyFacadeInterface $moneyFacade,
        MerchantSalesOrderMerchantUserGuiToUtilSanitizeInterface $sanitizeService,
        MerchantSalesOrderMerchantUserGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface $customerFacade,
        MerchantSalesOrderMerchantUserGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->merchantSalesOrderQuery = $merchantSalesOrderQuery;
        $this->moneyFacade = $moneyFacade;
        $this->sanitizeService = $sanitizeService;
        $this->utilDateTimeService = $utilDateTimeService;
        $this->customerFacade = $customerFacade;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->setSortable([
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE,
            SpyMerchantSalesOrderTableMap::COL_CREATED_AT,
            SpySalesOrderTableMap::COL_EMAIL,
            static::COL_ITEM_COUNT,
        ]);

        $config->setRawColumns([
            SpySalesOrderTableMap::COL_EMAIL,
            static::COL_FULL_CUSTOMER_NAME,
            static::COL_ACTIONS,
        ]);
        $config->setDefaultSortField(SpyMerchantSalesOrderTableMap::COL_ID_MERCHANT_SALES_ORDER, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            SpySalesOrderTableMap::COL_EMAIL,
            SpySalesOrderTableMap::COL_FIRST_NAME,
            SpySalesOrderTableMap::COL_LAST_NAME,
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $actions = [
            SpySalesOrderTableMap::COL_ORDER_REFERENCE => 'Order Reference',
            SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE => 'Marketplace Order Reference',
            SpyMerchantSalesOrderTableMap::COL_CREATED_AT => 'Created',
            static::COL_FULL_CUSTOMER_NAME => 'Customer',
            SpySalesOrderTableMap::COL_EMAIL => 'Email',
            static::COL_ORDER_STATE => 'Order State',
            SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL => 'Grand Total',
            static::COL_ITEM_COUNT => 'Number of items',
            static::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($actions);

        return $config;
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed>
     *
     * @module MerchantSalesOrder
     * @module MerchantOms
     * @module Sales
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function prepareQuery(): SpyMerchantSalesOrderQuery
    {
        $merchantReference = $this->merchantUserFacade->getCurrentMerchantUser()->getMerchant()->getMerchantReference();

        $this->merchantSalesOrderQuery
            ->groupByIdMerchantSalesOrder()
            ->joinOrder()
            ->joinMerchantSalesOrderTotal()
            ->useMerchantSalesOrderItemQuery()
                ->joinStateMachineItemState()
            ->endUse()
            ->where(SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE . '=?', $merchantReference)
            ->select([
                SpySalesOrderTableMap::COL_ORDER_REFERENCE,
                SpySalesOrderTableMap::COL_EMAIL,
                SpySalesOrderTableMap::COL_FIRST_NAME,
                SpySalesOrderTableMap::COL_LAST_NAME,
                SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL,
                SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE,
                SpySalesOrderTableMap::COL_SALUTATION,
                SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE,
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE,
                SpyMerchantSalesOrderTableMap::COL_CREATED_AT,
                SpyMerchantSalesOrderTableMap::COL_ID_MERCHANT_SALES_ORDER,
            ])
            ->withColumn(
                sprintf('GROUP_CONCAT(DISTINCT %s)', SpyStateMachineItemStateTableMap::COL_NAME),
                static::COL_ORDER_STATE
            )
            ->withColumn(
                sprintf('COUNT(%s)', SpyMerchantSalesOrderItemTableMap::COL_ID_MERCHANT_SALES_ORDER_ITEM),
                static::COL_ITEM_COUNT
            );

        return $this->merchantSalesOrderQuery;
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];

        foreach ($queryResults as $item) {
            $rowData = [
                SpySalesOrderTableMap::COL_ORDER_REFERENCE => $item[SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE],
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE => $item[SpySalesOrderTableMap::COL_ORDER_REFERENCE],
                SpyMerchantSalesOrderTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[SpyMerchantSalesOrderTableMap::COL_CREATED_AT]),
                static::COL_FULL_CUSTOMER_NAME => $this->formatFullCustomerName($item),
                SpySalesOrderTableMap::COL_EMAIL => $this->formatEmailAddress($item[SpySalesOrderTableMap::COL_EMAIL]),
                static::COL_ORDER_STATE => $item[static::COL_ORDER_STATE],
                SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL => $this->formatGrandTotal($item),
                static::COL_ITEM_COUNT => $item[static::COL_ITEM_COUNT],
                static::COL_ACTIONS => $this->buildLinks($item),
            ];

            $results[] = $rowData;
        }

        return $results;
    }

    /**
     * @phpstan-param array<string, mixed> $item
     *
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];
        $buttons[] = $this->generateViewButton(
            Url::generate(
                static::ROUTE_REDIRECT,
                [MerchantSalesOrderMerchantUserGuiConfig::REQUEST_PARAM_ID_MERCHANT_SALES_ORDER => $item[SpyMerchantSalesOrderTableMap::COL_ID_MERCHANT_SALES_ORDER]]
            ),
            'View'
        );

        return implode(' ', $buttons);
    }

    /**
     * @phpstan-param array<string, mixed> $item
     *
     * @param array $item
     *
     * @return string
     */
    protected function formatFullCustomerName(array $item): string
    {
        $salutation = $item[SpySalesOrderTableMap::COL_SALUTATION];

        $fullCustomerName = sprintf(
            '%s%s %s',
            $salutation ? $salutation . ' ' : '',
            $item[SpySalesOrderTableMap::COL_FIRST_NAME],
            $item[SpySalesOrderTableMap::COL_LAST_NAME]
        );

        $fullCustomerName = $this->sanitizeService->escapeHtml($fullCustomerName);

        if ($item[SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE]) {
            return $fullCustomerName;
        }

        $customerTransfer = $this->customerFacade->findByReference(
            $item[SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE]
        );

        if (!$customerTransfer) {
            return $fullCustomerName;
        }

        $url = Url::generate('/customer/view', [
            'id-customer' => $customerTransfer->getIdCustomer(),
        ]);

        return '<a href="' . $url . '">' . $fullCustomerName . '</a>';
    }

    /**
     * @param string $emailAddress
     *
     * @return string
     */
    protected function formatEmailAddress(string $emailAddress): string
    {
        $escapedEmailAddress = $this->sanitizeService->escapeHtml($emailAddress);
        $emailAddressLink = sprintf('<a href="mailto:%1$s">%1$s</a>', $escapedEmailAddress);

        return $emailAddressLink;
    }

    /**
     * @phpstan-param array<string, mixed> $item
     *
     * @param array $item
     *
     * @return string
     */
    protected function formatGrandTotal(array $item): string
    {
        $currencyIsoCode = $item[SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE];

        if (!$item[SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL]) {
            return $this->formatPrice(0, true, $currencyIsoCode);
        }

        return $this->formatPrice((int)$item[SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL], true, $currencyIsoCode);
    }

    /**
     * @param int $value
     * @param bool $includeSymbol
     * @param string|null $currencyIsoCode
     *
     * @return string
     */
    protected function formatPrice(int $value, bool $includeSymbol = true, ?string $currencyIsoCode = null): string
    {
        $moneyTransfer = $this->moneyFacade->fromInteger($value, $currencyIsoCode);

        if ($includeSymbol) {
            return $this->moneyFacade->formatWithSymbol($moneyTransfer);
        }

        return $this->moneyFacade->formatWithoutSymbol($moneyTransfer);
    }
}
