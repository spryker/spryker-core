<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommissionDataExport;

use Codeception\Actor;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportConnectionConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatConfigurationTransfer;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\MerchantCommissionDataExport\Business\MerchantCommissionDataExportFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantCommissionDataExportBusinessTester extends Actor
{
    use _generated\MerchantCommissionDataExportBusinessTesterActions;

    /**
     * @uses \Spryker\Service\DataExport\Writer\DataExportLocalWriter::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR
     *
     * @var string
     */
    protected const LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR = 'export_root_dir';

    /**
     * @var string
     */
    protected const EXPORT_ROOT_DIR = '{application_root_dir}/data/export';

    /**
     * @var string
     */
    protected const DESTINATION = 'php://output';

    /**
     * @var list<string>
     */
    protected const FIELDS = [
        'key',
        'name',
        'description',
        'valid_from',
        'valid_to',
        'is_active',
        'amount',
        'calculator_type_plugin',
        'group',
        'priority',
        'item_condition',
        'order_condition',
        'stores',
        'merchants_allow_list',
        'fixed_amount_configuration',
    ];

    /**
     * @var string
     */
    protected const FORMATTER_TYPE = 'csv';

    /**
     * @uses \Spryker\Service\DataExport\Plugin\DataExport\OutputStreamDataExportConnectionPlugin::CONNECTION_TYPE_OUTPUT_STREAM
     *
     * @var string
     */
    protected const CONNECTION_TYPE = 'output-stream';

    /**
     * @return void
     */
    public function ensureMerchantCommissionTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getMerchantCommissionQuery());
    }

    /**
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function createDataExportConfigurationTransfer(): DataExportConfigurationTransfer
    {
        $dataExportFormatConfigurationTransfer = (new DataExportFormatConfigurationTransfer())
            ->setType(static::FORMATTER_TYPE);
        $dataExportConnectionConfigurationTransfer = (new DataExportConnectionConfigurationTransfer())
            ->setType(static::CONNECTION_TYPE);

        return (new DataExportConfigurationTransfer())
            ->setFields(static::FIELDS)
            ->setDestination(static::DESTINATION)
            ->setFormat($dataExportFormatConfigurationTransfer)
            ->setConnection($dataExportConnectionConfigurationTransfer);
    }

    /**
     * @param string $exportedData
     *
     * @return array<string, string>
     */
    public function parseExportedData(string $exportedData): array
    {
        $parsedExportedData = array_map('str_getcsv', explode(PHP_EOL, trim($exportedData)));

        $header = array_shift($parsedExportedData);
        array_walk($parsedExportedData, function (&$dataRow) use ($header): void {
            $dataRow = array_combine($header, $dataRow);
        });

        return $parsedExportedData;
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected function getMerchantCommissionQuery(): SpyMerchantCommissionQuery
    {
        return SpyMerchantCommissionQuery::create();
    }
}
