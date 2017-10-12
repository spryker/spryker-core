<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Communication\Table;

use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class Payments extends AbstractTable
{
    const FIELD_VIEW = 'FIELD_VIEW';
    const URL_PAYOLUTION_DETAILS = '/payolution/details';
    const PARAM_ID_PAYMENT = 'id-payment';

    /**
     * @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery
     */
    private $paymentPayolutionQuery;

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery $paymentPayolutionQuery
     */
    public function __construct(SpyPaymentPayolutionQuery $paymentPayolutionQuery)
    {
        $this->paymentPayolutionQuery = $paymentPayolutionQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyPaymentPayolutionTableMap::COL_ID_PAYMENT_PAYOLUTION => 'Payment ID',
            SpyPaymentPayolutionTableMap::COL_FK_SALES_ORDER => 'Order ID',
            SpyPaymentPayolutionTableMap::COL_EMAIL => 'Email',
            SpyPaymentPayolutionTableMap::COL_CREATED_AT => 'Created',
            self::FIELD_VIEW => 'View',
        ]);

        $config->addRawColumn(self::FIELD_VIEW);

        $config->setSortable([
            SpyPaymentPayolutionTableMap::COL_CREATED_AT,
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
        $paymentItems = $this->runQuery($this->paymentPayolutionQuery, $config);
        $results = [];
        foreach ($paymentItems as $paymentItem) {
            $results[] = [
                SpyPaymentPayolutionTableMap::COL_ID_PAYMENT_PAYOLUTION => $paymentItem[SpyPaymentPayolutionTableMap::COL_ID_PAYMENT_PAYOLUTION],
                SpyPaymentPayolutionTableMap::COL_FK_SALES_ORDER => $paymentItem[SpyPaymentPayolutionTableMap::COL_FK_SALES_ORDER],
                SpyPaymentPayolutionTableMap::COL_EMAIL => $paymentItem[SpyPaymentPayolutionTableMap::COL_EMAIL],
                SpyPaymentPayolutionTableMap::COL_CREATED_AT => $paymentItem[SpyPaymentPayolutionTableMap::COL_CREATED_AT],
                self::FIELD_VIEW => implode(' ', $this->buildOptionsUrls($paymentItem)),
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
            Url::generate(self::URL_PAYOLUTION_DETAILS, [
                self::PARAM_ID_PAYMENT => $paymentItem[SpyPaymentPayolutionTableMap::COL_ID_PAYMENT_PAYOLUTION],
            ]),
            'View'
        );

        return $urls;
    }
}
