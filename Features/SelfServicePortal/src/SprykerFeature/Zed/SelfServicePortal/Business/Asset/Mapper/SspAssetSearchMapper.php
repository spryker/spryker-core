<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Mapper;

use Generated\Shared\Search\SspAssetIndexMap;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;

class SspAssetSearchMapper implements SspAssetSearchMapperInterface
{
    /**
     * @var string
     */
    protected const SEARCH_DATA_KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const SEARCH_DATA_KEY_SERIAL_NUMBER = 'serial_number';

    /**
     * @var string
     */
    protected const SEARCH_DATA_KEY_MODEL_IDS = 'model_ids';

    /**
     * @var string
     */
    protected const SEARCH_DATA_KEY_BUSINESS_UNIT_IDS = 'busines_unit_ids';

    /**
     * @var string
     */
    protected const SEARCH_DATA_KEY_COMPANY_IDS = 'company_ids';

    /**
     * @var string
     */
    protected const SEARCH_DATA_KEY_ID_OWNER_BUSINESS_UNIT = 'id_owner_business_unit';

    /**
     * @var string
     */
    protected const SEARCH_DATA_KEY_ID_OWNER_COMPANY_ID = 'id_owner_company_id';

    public function __construct(
        protected UtilEncodingServiceInterface $utilEncodingService,
        protected StoreFacadeInterface $storeFacade
    ) {
    }

    public function mapSspAssetCollectionTransferToSspAssetSearchCollectionTransfer(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        SspAssetSearchCollectionTransfer $sspAssetSearchCollectionTransfer
    ): SspAssetSearchCollectionTransfer {
        $storeTransfers = $this->storeFacade->getAllStores();
        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $sspAssetSearchTransfer = $this->mapSspAssetTransferToSspAssetSearchTransfer(
                $sspAssetTransfer,
                new SspAssetSearchTransfer(),
                $storeTransfers,
            );

            $sspAssetSearchCollectionTransfer->addSspAsset($sspAssetSearchTransfer);
        }

        return $sspAssetSearchCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetSearch> $sspAssetSearchEntities
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function mapSspAssetSearchEntitiesToSynchronizationDataTransfers(ObjectCollection $sspAssetSearchEntities): array
    {
        $synchronizationDataTransfers = [];

        foreach ($sspAssetSearchEntities as $sspAssetSearchEntity) {
            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($sspAssetSearchEntity->getData())
                ->setKey($sspAssetSearchEntity->getKey());
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\SspAssetSearchTransfer $sspAssetSearchTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return \Generated\Shared\Transfer\SspAssetSearchTransfer
     */
    protected function mapSspAssetTransferToSspAssetSearchTransfer(
        SspAssetTransfer $sspAssetTransfer,
        SspAssetSearchTransfer $sspAssetSearchTransfer,
        array $storeTransfers
    ): SspAssetSearchTransfer {
        $sspAssetSearchTransfer->setIdSspAsset($sspAssetTransfer->getIdSspAsset());
        $sspAssetSearchData = $sspAssetTransfer->toArray(true, true);
        $sspAssetSearchTransfer->setData(
            $this->mapSspAssetDataToSearchData($sspAssetSearchData, $storeTransfers),
        );
        $sspAssetSearchTransfer->setStructuredData(
            $this->utilEncodingService->encodeJson($sspAssetSearchData),
        );

        return $sspAssetSearchTransfer;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<string, mixed>
     */
    protected function mapSspAssetDataToSearchData(array $data, array $storeTransfers): array
    {
        $searchResultData = [
            static::SEARCH_DATA_KEY_NAME => $data[SspAssetTransfer::NAME] ?? null,
            static::SEARCH_DATA_KEY_SERIAL_NUMBER => $data[SspAssetTransfer::SERIAL_NUMBER] ?? null,
            static::SEARCH_DATA_KEY_MODEL_IDS => array_map(fn (array $model) => $model[SspModelTransfer::ID_SSP_MODEL], $data[SspAssetTransfer::SSP_MODELS] ?? []),
            static::SEARCH_DATA_KEY_BUSINESS_UNIT_IDS => array_map(fn (array $businessUnit) => $businessUnit[SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT][CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT], $data[SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS] ?? []),
            static::SEARCH_DATA_KEY_COMPANY_IDS => array_map(fn (array $businessUnit) => $businessUnit[SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT][CompanyBusinessUnitTransfer::COMPANY][CompanyTransfer::ID_COMPANY], $data[SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS] ?? []),
            static::SEARCH_DATA_KEY_ID_OWNER_BUSINESS_UNIT => $data[SspAssetTransfer::COMPANY_BUSINESS_UNIT][CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT] ?? null,
            static::SEARCH_DATA_KEY_ID_OWNER_COMPANY_ID => $data[SspAssetTransfer::COMPANY_BUSINESS_UNIT][CompanyBusinessUnitTransfer::FK_COMPANY] ?? null,
        ];

        $fullTextBoosted = $this->buildFullTextBoosted($data);
        $suggestionTerms = $this->buildSuggestionTerms($data);
        $completionTerms = $this->buildCompletionTerms($data);

        return [
            SspAssetIndexMap::TYPE => SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME,
            SspAssetIndexMap::SEARCH_RESULT_DATA => $searchResultData,
            SspAssetIndexMap::FULL_TEXT_BOOSTED => $fullTextBoosted,
            SspAssetIndexMap::SUGGESTION_TERMS => $suggestionTerms,
            SspAssetIndexMap::COMPLETION_TERMS => $completionTerms,
            SspAssetIndexMap::STORE => array_map(fn (StoreTransfer $storeTransfer) => $storeTransfer->getName(), $storeTransfers),
            SspAssetIndexMap::ID_OWNER_BUSINESS_UNIT => $data[SspAssetTransfer::COMPANY_BUSINESS_UNIT][CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT] ?? null,
            SspAssetIndexMap::ID_OWNER_COMPANY_ID => $data[SspAssetTransfer::COMPANY_BUSINESS_UNIT][CompanyBusinessUnitTransfer::FK_COMPANY] ?? null,
            SspAssetIndexMap::COMPANY_IDS => array_map(fn (array $businessUnit) => $businessUnit[SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT][CompanyBusinessUnitTransfer::COMPANY][CompanyTransfer::ID_COMPANY], $data[SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS]),
            SspAssetIndexMap::BUSINESS_UNIT_IDS => array_map(fn (array $businessUnit) => $businessUnit[SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT][CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT], $data[SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS]),
        ];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string>
     */
    protected function buildFullTextBoosted(array $data): array
    {
        $fullTextBoosted = [];

        if (isset($data[SspAssetTransfer::NAME])) {
            $fullTextBoosted[] = $data[SspAssetTransfer::NAME];
        }

        if (isset($data[SspAssetTransfer::SSP_MODELS])) {
            foreach ($data[SspAssetTransfer::SSP_MODELS] as $model) {
                $fullTextBoosted[] = $model[SspModelTransfer::NAME];
            }
        }

        if (isset($data[SspAssetTransfer::SERIAL_NUMBER])) {
            $fullTextBoosted[] = $data[SspAssetTransfer::SERIAL_NUMBER];
        }

        return $fullTextBoosted;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string>
     */
    protected function buildSuggestionTerms(array $data): array
    {
        $suggestionTerms = [];

        if (isset($data[SspAssetTransfer::NAME])) {
            $suggestionTerms[] = $data[SspAssetTransfer::NAME];
        }

        if (isset($data[SspAssetTransfer::SSP_MODELS])) {
            foreach ($data[SspAssetTransfer::SSP_MODELS] as $model) {
                $suggestionTerms[] = $model[SspModelTransfer::NAME];
            }
        }

        return $suggestionTerms;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string>
     */
    protected function buildCompletionTerms(array $data): array
    {
        $suggestionTerms = [];

        if (isset($data[SspAssetTransfer::NAME])) {
            $suggestionTerms[] = $data[SspAssetTransfer::NAME];
        }

        if (isset($data[SspAssetTransfer::SSP_MODELS])) {
            foreach ($data[SspAssetTransfer::SSP_MODELS] as $model) {
                $suggestionTerms[] = $model[SspModelTransfer::NAME];
            }
        }

        return $suggestionTerms;
    }
}
