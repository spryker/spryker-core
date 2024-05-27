<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Persistence;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionAmountTableMap;
use Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\AssociativeArrayFormatter;

/**
 * @method \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportPersistenceFactory getFactory()
 */
class MerchantCommissionDataExportRepository extends AbstractRepository implements MerchantCommissionDataExportRepositoryInterface
{
    /**
     * @var string
     */
    protected const KEY_STORES = 'stores';

    /**
     * @var string
     */
    protected const KEY_MERCHANTS_ALLOW_LIST = 'merchants_allow_list';

    /**
     * @var string
     */
    protected const KEY_FIXED_AMOUNT_CONFIGURATION = 'fixed_amount_configuration';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataExport\Business\Exporter\MerchantCommissionDataExporter::FILTER_CRITERIA_KEY_OFFSET
     *
     * @var string
     */
    protected const FILTER_CRITERIA_PARAM_OFFSET = 'offset';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataExport\Business\Exporter\MerchantCommissionDataExporter::FILTER_CRITERIA_KEY_LIMIT
     *
     * @var string
     */
    protected const FILTER_CRITERIA_PARAM_LIMIT = 'limit';

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantCommissionData(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportBatchTransfer {
        $selectedColumns = $this->getSelectedColumns($dataExportConfigurationTransfer);
        $selectedFields = array_keys($selectedColumns);

        $filterCriteria = $dataExportConfigurationTransfer->getFilterCriteria();
        $dataExportBatchTransfer = (new DataExportBatchTransfer())
            ->setOffset($filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET])
            ->setFields($selectedFields)
            ->setData([]);

        $merchantCommissionQuery = $this->getFactory()
            ->getMerchantCommissionPropelQuery()
            ->joinMerchantCommissionGroup()
            ->offset($filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET])
            ->limit($filterCriteria[static::FILTER_CRITERIA_PARAM_LIMIT])
            ->select([SpyMerchantCommissionTableMap::COL_ID_MERCHANT_COMMISSION]);

        foreach ($selectedColumns as $fieldName => $columnName) {
            if ($columnName === null) {
                continue;
            }

            $merchantCommissionQuery->addAsColumn(sprintf('"%s"', $fieldName), $columnName);
        }
        $merchantCommissionQuery = $this->addSelectedRelationFieldsToQuery($merchantCommissionQuery, $selectedFields);

        $merchantCommissionData = $merchantCommissionQuery->setFormatter(AssociativeArrayFormatter::class)->find()->getData();
        if ($merchantCommissionData === []) {
            return $dataExportBatchTransfer;
        }

        if (array_key_exists(static::KEY_FIXED_AMOUNT_CONFIGURATION, $selectedColumns)) {
            $merchantCommissionData = $this->expandMerchantCommissionDataWithMerchantCommissionAmount($merchantCommissionData);
        }

        $merchantCommissionData = $this->formatMerchantCommissionDataKeys($merchantCommissionData);
        $merchantCommissionData = $this->getFactory()
            ->createMerchantCommissionMapper()
            ->mapMerchantCommissionDataBySelectedFields($merchantCommissionData, $selectedFields);

        return $dataExportBatchTransfer->setData($merchantCommissionData);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return array<string, string|null>
     */
    protected function getSelectedColumns(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $fieldMapping = $this->getFactory()
            ->createMerchantCommissionMapper()
            ->getFieldMapping();

        return array_intersect_key($fieldMapping, array_flip($dataExportConfigurationTransfer->getFields()));
    }

    /**
     * @module Merchant
     * @module MerchantCommission
     * @module Store
     *
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery $merchantCommissionQuery
     * @param list<string> $selectedFields
     *
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected function addSelectedRelationFieldsToQuery(
        SpyMerchantCommissionQuery $merchantCommissionQuery,
        array $selectedFields
    ): SpyMerchantCommissionQuery {
        $selectedFields = array_flip($selectedFields);
        if (
            !isset($selectedFields[static::KEY_STORES])
            && !isset($selectedFields[static::KEY_MERCHANTS_ALLOW_LIST])
            && !isset($selectedFields[static::KEY_FIXED_AMOUNT_CONFIGURATION])
        ) {
            return $merchantCommissionQuery;
        }

        $merchantCommissionQuery->groupByIdMerchantCommission();
        if (isset($selectedFields[static::KEY_STORES])) {
            $merchantCommissionQuery
                ->useMerchantCommissionStoreQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinStore()
                ->endUse()
                ->withColumn(sprintf('GROUP_CONCAT(DISTINCT %s)', SpyStoreTableMap::COL_NAME), static::KEY_STORES);
        }

        if (isset($selectedFields[static::KEY_MERCHANTS_ALLOW_LIST])) {
            $merchantCommissionQuery
                ->useMerchantCommissionMerchantQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinMerchant()
                ->endUse()
                ->withColumn(sprintf('GROUP_CONCAT(DISTINCT %s)', SpyMerchantTableMap::COL_MERCHANT_REFERENCE), static::KEY_MERCHANTS_ALLOW_LIST);
        }

        return $merchantCommissionQuery;
    }

    /**
     * @module Currency
     *
     * @param list<array<string, mixed>> $merchantCommissionData
     *
     * @return list<array<string, mixed>>
     */
    protected function expandMerchantCommissionDataWithMerchantCommissionAmount(array $merchantCommissionData): array
    {
        $merchantCommissionIds = $this->extractMerchantCommissionIds($merchantCommissionData);

        $merchantCommissionAmountData = $this->getFactory()
            ->getMerchantCommissionAmountPropelQuery()
            ->joinCurrency()
            ->filterByFkMerchantCommission_In($merchantCommissionIds)
            ->select([
                SpyMerchantCommissionAmountTableMap::COL_FK_MERCHANT_COMMISSION,
                SpyMerchantCommissionAmountTableMap::COL_NET_AMOUNT,
                SpyMerchantCommissionAmountTableMap::COL_GROSS_AMOUNT,
                SpyCurrencyTableMap::COL_CODE,
            ])
            ->setFormatter(AssociativeArrayFormatter::class)
            ->find()
            ->getData();

        $merchantCommissionAmountDataGroupedByIdMerchantCommission = $this->getMerchantCommissionAmountDataGroupedByIdMerchantCommission(
            $merchantCommissionAmountData,
        );
        foreach ($merchantCommissionData as $key => $merchantCommissionRowData) {
            $idMerchantCommission = $merchantCommissionRowData[SpyMerchantCommissionTableMap::COL_ID_MERCHANT_COMMISSION];
            $merchantCommissionAmountData = $merchantCommissionAmountDataGroupedByIdMerchantCommission[$idMerchantCommission] ?? [];
            if ($merchantCommissionAmountData === []) {
                $merchantCommissionData[$key][static::KEY_FIXED_AMOUNT_CONFIGURATION] = '';

                continue;
            }

            $merchantCommissionData[$key][static::KEY_FIXED_AMOUNT_CONFIGURATION] = $this->formatFixedAmountConfiguration(
                $merchantCommissionAmountData,
            );
        }

        return $merchantCommissionData;
    }

    /**
     * @param list<array<string, mixed>> $merchantCommissionData
     *
     * @return list<int>
     */
    protected function extractMerchantCommissionIds(array $merchantCommissionData): array
    {
        return array_column($merchantCommissionData, SpyMerchantCommissionTableMap::COL_ID_MERCHANT_COMMISSION);
    }

    /**
     * @param list<array<string, mixed>> $merchantCommissionAmountData
     *
     * @return array<int, list<array<string, mixed>>>
     */
    protected function getMerchantCommissionAmountDataGroupedByIdMerchantCommission(array $merchantCommissionAmountData): array
    {
        $groupedMerchantCommissionAmountData = [];
        foreach ($merchantCommissionAmountData as $merchantCommissionAmountRowData) {
            $idMerchantCommission = (int)$merchantCommissionAmountRowData[SpyMerchantCommissionAmountTableMap::COL_FK_MERCHANT_COMMISSION];
            $groupedMerchantCommissionAmountData[$idMerchantCommission][] = $merchantCommissionAmountRowData;
        }

        return $groupedMerchantCommissionAmountData;
    }

    /**
     * @param list<array<string, mixed>> $merchantCommissionData
     *
     * @return list<array<string, mixed>>
     */
    protected function formatMerchantCommissionDataKeys(array $merchantCommissionData): array
    {
        foreach ($merchantCommissionData as $key => $merchantCommissionDataRow) {
            foreach ($merchantCommissionDataRow as $rowKey => $rowValue) {
                unset($merchantCommissionDataRow[$rowKey]);

                $trimmedRowKey = trim($rowKey, '"');
                $merchantCommissionDataRow[$trimmedRowKey] = $rowValue;
            }

            unset($merchantCommissionDataRow[SpyMerchantCommissionTableMap::COL_ID_MERCHANT_COMMISSION]);
            $merchantCommissionData[$key] = $merchantCommissionDataRow;
        }

        return $merchantCommissionData;
    }

    /**
     * @param list<array<string, mixed>> $merchantCommissionAmountData
     *
     * @return string
     */
    protected function formatFixedAmountConfiguration(array $merchantCommissionAmountData): string
    {
        $fixedAmountConfiguration = [];
        foreach ($merchantCommissionAmountData as $merchantCommissionAmountRowData) {
            $fixedAmountConfiguration[] = sprintf(
                '%s|%s|%s',
                $merchantCommissionAmountRowData[SpyCurrencyTableMap::COL_CODE],
                $merchantCommissionAmountRowData[SpyMerchantCommissionAmountTableMap::COL_NET_AMOUNT] / 100,
                $merchantCommissionAmountRowData[SpyMerchantCommissionAmountTableMap::COL_GROSS_AMOUNT] / 100,
            );
        }

        return implode(',', $fixedAmountConfiguration);
    }
}
