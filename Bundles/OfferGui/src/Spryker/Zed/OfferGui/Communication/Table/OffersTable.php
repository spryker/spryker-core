<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Table;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Offer\Persistence\Map\SpyOfferTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\OfferGui\OfferGuiConfig;

class OffersTable extends AbstractTable
{
    protected const URL_OFFER_GUI_EDIT = '/offer-gui/edit/';
    protected const URL_OFFER_GUI_VIEW_DETAILS = '/offer-gui/view/details';
    protected const URL_OFFER_GUI_PRINT_VERSION = '/offer-gui/view/print-version';
    protected const URL_OFFER_GUI_SUGGEST = '/offer-gui/copy';
    protected const URL_OFFER_GUI_PLACE_ORDER = '/offer-gui/place-order/';
    protected const URL_PARAM_ID_OFFER = 'id-offer';

    protected const COL_ID_OFFER = SpyOfferTableMap::COL_ID_OFFER;
    protected const COL_CREATED_AT = SpyOfferTableMap::COL_CREATED_AT;
    protected const COL_CUSTOMER_REFERENCE = SpyOfferTableMap::COL_CUSTOMER_REFERENCE;
    protected const COL_EMAIL = 'email';
    protected const COL_GRAND_TOTAL = 'grand_total';
    protected const COL_CONTACT_DATE = SpyOfferTableMap::COL_CONTACT_DATE;
    protected const COL_CONTACT_PERSON = SpyOfferTableMap::COL_CONTACT_PERSON;
    protected const COL_NOTE = SpyOfferTableMap::COL_NOTE;
    protected const COL_URL = 'url';
    protected const COL_STATUS = 'status';

    /**
     * @var \Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * @var \Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilSanitizeServiceInterface
     */
    protected $sanitizeService;

    /**
     * @var \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\OfferGui\OfferGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilderInterface $queryBuilder
     * @param \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilSanitizeServiceInterface $sanitizeService
     * @param \Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\OfferGui\OfferGuiConfig $config
     */
    public function __construct(
        OffersTableQueryBuilderInterface $queryBuilder,
        OfferGuiToMoneyFacadeInterface $moneyFacade,
        OfferGuiToCustomerFacadeInterface $customerFacade,
        OfferGuiToUtilSanitizeServiceInterface $sanitizeService,
        OfferGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        OfferGuiConfig $config
    ) {
        $this->queryBuilder = $queryBuilder;
        $this->moneyFacade = $moneyFacade;
        $this->sanitizeService = $sanitizeService;
        $this->utilDateTimeService = $utilDateTimeService;
        $this->customerFacade = $customerFacade;
        $this->config = $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader($this->getHeaderFields());
        $config->setSearchable($this->getSearchableFields());
        $config->setSortable($this->getSortableFields());

        $config->addRawColumn(static::COL_URL);
        $config->addRawColumn(static::COL_CUSTOMER_REFERENCE);
        $config->addRawColumn(static::COL_EMAIL);
        $config->addRawColumn(static::COL_STATUS);

        $config->setDefaultSortField(static::COL_ID_OFFER, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->queryBuilder->buildQuery();
        $queryResults = $this->runQuery($query, $config);

        return $this->formatQueryData($queryResults);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getGrandTotal(QuoteTransfer $quoteTransfer)
    {
        $currencyIsoCode = $quoteTransfer->getCurrency()->getCode();
        if (!$quoteTransfer->getTotals()->getGrandTotal()) {
            return $this->formatPrice(0, true, $currencyIsoCode);
        }

        return $this->formatPrice($quoteTransfer->getTotals()->getGrandTotal(), true, $currencyIsoCode);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string
     */
    protected function formatCustomer(?CustomerTransfer $customerTransfer)
    {
        if (!$customerTransfer) {
            return 'No reference';
        }

        $customer = sprintf(
            '%s%s %s',
            $customerTransfer->getSalutation(),
            $customerTransfer->getFirstName(),
            $customerTransfer->getLastName()
        );

        $customer = $this->sanitizeService->escapeHtml($customer);

        if (!$customerTransfer) {
            return $customer;
        }

        $url = Url::generate('/customer/view', [
            'id-customer' => $customerTransfer->getIdCustomer(),
        ]);

        $customer = '<a href="' . $url . '">' . $customer . '</a>';

        return $customer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    protected function formatEmailAddress(?CustomerTransfer $customerTransfer)
    {
        if (!$customerTransfer) {
            return '';
        }

        $escapedEmailAddress = $this->sanitizeService->escapeHtml($customerTransfer->getEmail());
        $emailAddressLink = sprintf('<a href="mailto:%1$s">%1$s</a>', $escapedEmailAddress);

        return $emailAddressLink;
    }

    /**
     * @param string $itemStateNamesCsv
     *
     * @return string
     */
    protected function groupItemStateNames($itemStateNamesCsv)
    {
        $itemStateNames = explode(',', $itemStateNamesCsv);
        $itemStateNames = array_map('trim', $itemStateNames);
        $distinctItemStateNames = array_unique($itemStateNames);
        $distinctItemStateNamesCsv = implode(', ', $distinctItemStateNames);

        return $distinctItemStateNamesCsv;
    }

    /**
     * @param int $value
     * @param bool $includeSymbol
     * @param null|string $currencyIsoCode
     *
     * @return string
     */
    protected function formatPrice($value, $includeSymbol = true, $currencyIsoCode = null)
    {
        $moneyTransfer = $this->moneyFacade->fromInteger($value, $currencyIsoCode);

        if ($includeSymbol) {
            return $this->moneyFacade->formatWithSymbol($moneyTransfer);
        }

        return $this->moneyFacade->formatWithoutSymbol($moneyTransfer);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionUrls(array $item)
    {
        $urls = [];

        if ($this->isClosed($item)) {
            $urls[] = $this->generateEditButton(
                Url::generate(static::URL_OFFER_GUI_EDIT, [
                    static::URL_PARAM_ID_OFFER => $item[SpyOfferTableMap::COL_ID_OFFER],
                ]),
                'Edit'
            );
        }

        $urls[] = $this->generateViewButton(
            Url::generate(static::URL_OFFER_GUI_VIEW_DETAILS, [
                static::URL_PARAM_ID_OFFER => $item[SpyOfferTableMap::COL_ID_OFFER],
            ]),
            'View'
        );

        $urls[] = $this->generateViewButton(
            Url::generate(static::URL_OFFER_GUI_PRINT_VERSION, [
                static::URL_PARAM_ID_OFFER => $item[SpyOfferTableMap::COL_ID_OFFER],
            ]),
            'Print version',
            ['target' => '_blank']
        );

        $urls[] = $this->generateCreateButton(
            Url::generate(static::URL_OFFER_GUI_SUGGEST, [
                static::URL_PARAM_ID_OFFER => $item[SpyOfferTableMap::COL_ID_OFFER],
            ]),
            'Suggest to'
        );

        if ($this->isClosed($item)) {
            $urls[] = $this->generateCreateButton(
                Url::generate(static::URL_OFFER_GUI_PLACE_ORDER, [
                    static::URL_PARAM_ID_OFFER => $item[SpyOfferTableMap::COL_ID_OFFER],
                ]),
                'Place order'
            );
        }

        return $urls;
    }

    /**
     * @param array $item
     *
     * @return bool
     */
    protected function isClosed(array $item)
    {
        return $item[SpyOfferTableMap::COL_STATUS] !== OfferGuiConfig::STATUS_CLOSE;
    }

    /**
     * @return array
     */
    protected function getHeaderFields()
    {
        return [
            static::COL_ID_OFFER => '#',
            static::COL_CREATED_AT => 'Created',
            static::COL_CUSTOMER_REFERENCE => 'Customer Full Name',
            static::COL_EMAIL => 'Email',
            static::COL_GRAND_TOTAL => 'GrandTotal',
            static::COL_CONTACT_DATE => 'Next contact date',
            static::COL_CONTACT_PERSON => 'Person in charge',
            static::COL_STATUS => 'Offer state',
            static::COL_NOTE => 'Comment',
            static::COL_URL => 'Actions',
        ];
    }

    /**
     * @return array
     */
    protected function getSearchableFields()
    {
        return [
            static::COL_ID_OFFER,
            static::COL_CREATED_AT,
            static::COL_CUSTOMER_REFERENCE,
        ];
    }

    /**
     * @return array
     */
    protected function getSortableFields()
    {
        return [
            static::COL_ID_OFFER,
            static::COL_CREATED_AT,
        ];
    }

    /**
     * @param array $queryResults
     *
     * @return array
     */
    protected function formatQueryData(array $queryResults)
    {
        $results = [];
        foreach ($queryResults as $item) {
            $quoteTransfer = $this->mapQuote($item);
            $customerTransfer = $this->getCustomer($item);

            $results[] = [
                static::COL_ID_OFFER => $item[SpyOfferTableMap::COL_ID_OFFER],
                static::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[SpyOfferTableMap::COL_CREATED_AT]),
                static::COL_CUSTOMER_REFERENCE => $this->formatCustomer($customerTransfer),
                static::COL_EMAIL => $this->formatEmailAddress($customerTransfer),
                static::COL_GRAND_TOTAL => $this->getGrandTotal($quoteTransfer),
                static::COL_CONTACT_DATE => $this->utilDateTimeService->formatDate($item[SpyOfferTableMap::COL_CONTACT_DATE]),
                static::COL_CONTACT_PERSON => $item[SpyOfferTableMap::COL_CONTACT_PERSON],
                static::COL_STATUS => $item[SpyOfferTableMap::COL_STATUS],
                static::COL_NOTE => $item[SpyOfferTableMap::COL_NOTE],
                static::COL_URL => implode(' ', $this->createActionUrls($item)),
            ];
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapQuote(array $item)
    {
        $quoteTransfer = new QuoteTransfer();

        if (empty($item[SpyOfferTableMap::COL_QUOTE_DATA])) {
            return $quoteTransfer;
        }

        $quoteArray = json_decode($item[SpyOfferTableMap::COL_QUOTE_DATA], true);
        $quoteTransfer->fromArray($quoteArray, true);

        return $quoteTransfer;
    }

    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomer(array $item): ?CustomerTransfer
    {
        return $this->customerFacade->findCustomerByReference(
            $item[SpyOfferTableMap::COL_CUSTOMER_REFERENCE]
        );
    }
}
