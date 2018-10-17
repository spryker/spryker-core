<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class MerchantRelationshipSalesOrderThresholdDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD = 'merchant-relationship-sales-order-threshold';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantRelationshipSalesOrderThresholdDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            implode(DIRECTORY_SEPARATOR, [$this->getModuleDataImportDirectory(), 'sales_order_threshold_per_merchant_relationship.csv']),
            static::IMPORT_TYPE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD
        );
    }

    /**
     * @return string
     */
    protected function getModuleDataImportDirectory(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->getModuleRoot(),
            'data',
            'import',
        ]);
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        return realpath(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            '..',
            '..',
            '..',
        ]));
    }
}
