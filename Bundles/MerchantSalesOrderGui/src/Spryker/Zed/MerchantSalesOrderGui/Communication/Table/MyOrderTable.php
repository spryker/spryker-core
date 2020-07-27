<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Table;

use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderItemTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTotalsTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToCustomerFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMoneyFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilSanitizeInterface;
use Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig;

class MyOrderTable extends AbstractTable
{
    protected const MERCHANT_REFERENCE = 'MER000001';

    protected const REQUEST_ID_MERCHANT = 'id-order';

    public const COL_FULL_CUSTOMER_NAME = 'fullCstomerName';
    public const COL_COUNT_ITEM = 'countItem';
    public const COL_ORDER_STATE = 'orderState';
    public const COL_ACTIONS = 'actions';

    /**
     * @var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected $merchantSalesOrderQuery;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilSanitizeInterface
     */
    protected $sanitizeService;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilSanitizeInterface $sanitizeService
     * @param \Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantSalesOrderGuiToMoneyFacadeInterface $moneyFacade,
        MerchantSalesOrderGuiToUtilSanitizeInterface $sanitizeService,
        MerchantSalesOrderGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        MerchantSalesOrderGuiToCustomerFacadeInterface $customerFacade,
        MerchantSalesOrderGuiToMerchantUserFacadeInterface $merchantUserFacade
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
            static::COL_COUNT_ITEM,
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
            static::COL_COUNT_ITEM => 'Number of items',
            static::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($actions);

        return $config;
    }

    /**
     * @module MerchantOms
     * @module MerchantSalesOrder
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function prepareQuery(): SpyMerchantSalesOrderQuery
    {
        $merchantReference = $this->merchantUserFacade->getCurrentMerchantUser()->getMerchant()->getMerchantReference();

        $this->merchantSalesOrderQuery
            ->groupByIdMerchantSalesOrder()
            ->useOrderQuery()
            ->endUse()
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
                sprintf('GROUP_CONCAT(%s)', SpyStateMachineItemStateTableMap::COL_NAME),
                static::COL_ORDER_STATE
            )
            ->withColumn(
                sprintf('count(%s)', SpyMerchantSalesOrderItemTableMap::COL_ID_MERCHANT_SALES_ORDER_ITEM),
                static::COL_COUNT_ITEM
            );

        return $this->merchantSalesOrderQuery;
    }

    /**
     * @phpstan-return array<string, mixed>
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
                static::COL_FULL_CUSTOMER_NAME => $this->formatCustomer($item),
                SpySalesOrderTableMap::COL_EMAIL => $this->formatEmailAddress($item[SpySalesOrderTableMap::COL_EMAIL]),
                static::COL_ORDER_STATE => $item[static::COL_ORDER_STATE],
                SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL => $this->getGrandTotal($item),
                static::COL_COUNT_ITEM => $item[static::COL_COUNT_ITEM],
                static::COL_ACTIONS => $this->buildLinks($item),
            ];

            $results[] = $rowData;
        }
        unset($queryResults);

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
                MerchantSalesOrderGuiConfig::URL_DETAIL,
                [MerchantSalesOrderGuiConfig::REQUEST_ID_MERCHANT_SALES_ORDER => $item[SpyMerchantSalesOrderTableMap::COL_ID_MERCHANT_SALES_ORDER]]
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
    protected function formatCustomer(array $item)
    {
        $salutation = $item[SpySalesOrderTableMap::COL_SALUTATION];

        $customer = sprintf(
            '%s%s %s',
            $salutation ? $salutation . ' ' : '',
            $item[SpySalesOrderTableMap::COL_FIRST_NAME],
            $item[SpySalesOrderTableMap::COL_LAST_NAME]
        );

        $customer = $this->sanitizeService->escapeHtml($customer);

        if (isset($item[SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE])) {
            $customerTransfer = $this->customerFacade->findByReference(
                $item[SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE]
            );

            if (!$customerTransfer) {
                return $customer;
            }
            $url = Url::generate('/customer/view', [
                'id-customer' => $customerTransfer->getIdCustomer(),
            ]);
            $customer = '<a href="' . $url . '">' . $customer . '</a>';
        }

        return $customer;
    }

    /**
     * @param string $emailAddress
     *
     * @return string
     */
    protected function formatEmailAddress(string $emailAddress)
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
    protected function getGrandTotal(array $item)
    {
        $currencyIsoCode = $item[SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE];
        if (!isset($item[SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL])) {
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
    protected function formatPrice(int $value, bool $includeSymbol = true, ?string $currencyIsoCode = null)
    {
        $moneyTransfer = $this->moneyFacade->fromInteger($value, $currencyIsoCode);

        if ($includeSymbol) {
            return $this->moneyFacade->formatWithSymbol($moneyTransfer);
        }

        return $this->moneyFacade->formatWithoutSymbol($moneyTransfer);
    }
}
