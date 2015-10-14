<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Communication\Table;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Map\SpyPaymentPayolutionTableMap;

class Payments extends AbstractTable
{

    const FIELD_VIEW = 'FIELD_VIEW';

    /**
     * @var SpyPaymentPayolutionQuery
     */
    private $paymentPayolutionQuery;

    /**
     * @param SpyPaymentPayolutionQuery $paymentPayolutionQuery
     */
    public function __construct(SpyPaymentPayolutionQuery $paymentPayolutionQuery)
    {
        $this->paymentPayolutionQuery = $paymentPayolutionQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
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

        $config->setSortable([
            SpyPaymentPayolutionTableMap::COL_CREATED_AT,
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
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
                self::FIELD_VIEW => sprintf(
                    '<a class="btn btn-primary" href="/payolution/details?id-payment=%s">View</a>',
                    $paymentItem[SpyPaymentPayolutionTableMap::COL_ID_PAYMENT_PAYOLUTION]
                ),
            ];
        }

        return $results;
    }

}
