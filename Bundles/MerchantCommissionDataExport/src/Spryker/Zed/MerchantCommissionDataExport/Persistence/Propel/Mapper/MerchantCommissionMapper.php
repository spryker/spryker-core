<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Persistence\Propel\Mapper;

use Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionGroupTableMap;
use Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap;

class MerchantCommissionMapper
{
    /**
     * @var array<string, string|null>
     */
    protected const FIELD_MAPPING = [
        'key' => SpyMerchantCommissionTableMap::COL_KEY,
        'name' => SpyMerchantCommissionTableMap::COL_NAME,
        'description' => SpyMerchantCommissionTableMap::COL_DESCRIPTION,
        'valid_from' => SpyMerchantCommissionTableMap::COL_VALID_FROM,
        'valid_to' => SpyMerchantCommissionTableMap::COL_VALID_TO,
        'is_active' => SpyMerchantCommissionTableMap::COL_IS_ACTIVE,
        'amount' => SpyMerchantCommissionTableMap::COL_AMOUNT,
        'calculator_type_plugin' => SpyMerchantCommissionTableMap::COL_CALCULATOR_TYPE_PLUGIN,
        'priority' => SpyMerchantCommissionTableMap::COL_PRIORITY,
        'item_condition' => SpyMerchantCommissionTableMap::COL_ITEM_CONDITION,
        'order_condition' => SpyMerchantCommissionTableMap::COL_ORDER_CONDITION,
        'group' => SpyMerchantCommissionGroupTableMap::COL_KEY,
        'stores' => null,
        'merchants_allow_list' => null,
        'fixed_amount_configuration' => null,
    ];

    /**
     * @return array<string, string|null>
     */
    public function getFieldMapping(): array
    {
        return static::FIELD_MAPPING;
    }

    /**
     * @param list<array<string, mixed>> $merchantCommissionData
     * @param list<string> $selectedFields
     *
     * @return list<array<string, mixed>>
     */
    public function mapMerchantCommissionDataBySelectedFields(array $merchantCommissionData, array $selectedFields): array
    {
        foreach ($merchantCommissionData as $key => $merchantCommissionRowData) {
            $merchantCommissionRowData = $this->sortRowDataBySelectedFields($merchantCommissionRowData, $selectedFields);

            $merchantCommissionData[$key] = $merchantCommissionRowData;
        }

        return $merchantCommissionData;
    }

    /**
     * @param array<string, mixed> $rowData
     * @param list<string> $selectedFields
     *
     * @return array<string, mixed>
     */
    protected function sortRowDataBySelectedFields(array $rowData, array $selectedFields): array
    {
        return array_merge(array_flip($selectedFields), $rowData);
    }
}
