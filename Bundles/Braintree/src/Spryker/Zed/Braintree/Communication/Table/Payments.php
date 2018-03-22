<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Communication\Table;

use Orm\Zed\Braintree\Persistence\Map\SpyPaymentBraintreeTableMap;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class Payments extends AbstractTable
{
    const FIELD_VIEW = 'FIELD_VIEW';
    const URL_BRAINTREE_DETAILS = '/braintree/details';
    const PARAM_ID_PAYMENT = 'id-payment';

    /**
     * @var \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeQuery
     */
    private $paymentBraintreeQuery;

    /**
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeQuery $paymentBraintreeQuery
     */
    public function __construct(SpyPaymentBraintreeQuery $paymentBraintreeQuery)
    {
        $this->paymentBraintreeQuery = $paymentBraintreeQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyPaymentBraintreeTableMap::COL_ID_PAYMENT_BRAINTREE => 'Payment ID',
            SpyPaymentBraintreeTableMap::COL_FK_SALES_ORDER => 'Order ID',
            SpyPaymentBraintreeTableMap::COL_EMAIL => 'Email',
            SpyPaymentBraintreeTableMap::COL_CREATED_AT => 'Created',
            static::FIELD_VIEW => 'View',
        ]);

        $config->addRawColumn(static::FIELD_VIEW);

        $config->setSortable([
            SpyPaymentBraintreeTableMap::COL_CREATED_AT,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $paymentItems = $this->runQuery($this->paymentBraintreeQuery, $config);
        $results = [];
        foreach ($paymentItems as $paymentItem) {
            $results[] = [
                SpyPaymentBraintreeTableMap::COL_ID_PAYMENT_BRAINTREE => $paymentItem[SpyPaymentBraintreeTableMap::COL_ID_PAYMENT_BRAINTREE],
                SpyPaymentBraintreeTableMap::COL_FK_SALES_ORDER => $paymentItem[SpyPaymentBraintreeTableMap::COL_FK_SALES_ORDER],
                SpyPaymentBraintreeTableMap::COL_EMAIL => $paymentItem[SpyPaymentBraintreeTableMap::COL_EMAIL],
                SpyPaymentBraintreeTableMap::COL_CREATED_AT => $paymentItem[SpyPaymentBraintreeTableMap::COL_CREATED_AT],
                static::FIELD_VIEW => implode(' ', $this->buildOptionsUrls($paymentItem)),
            ];
        }

        return $results;
    }

    /**
     * @param array $paymentItem
     *
     * @return array
     */
    protected function buildOptionsUrls(array $paymentItem)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate(static::URL_BRAINTREE_DETAILS, [
                static::PARAM_ID_PAYMENT => $paymentItem[SpyPaymentBraintreeTableMap::COL_ID_PAYMENT_BRAINTREE],
            ]),
            'View'
        );

        return $urls;
    }
}
