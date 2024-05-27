<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Importer;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionCreatorInterface;
use Spryker\Zed\MerchantCommission\Business\Grouper\MerchantCommissionGrouperInterface;
use Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionUpdaterInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\MerchantCommissionImportValidatorInterface;

class MerchantCommissionImporter implements MerchantCommissionImporterInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Grouper\MerchantCommissionGrouperInterface
     */
    protected MerchantCommissionGrouperInterface $merchantCommissionGrouper;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\MerchantCommissionImportValidatorInterface
     */
    protected MerchantCommissionImportValidatorInterface $merchantCommissionImportValidator;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionCreatorInterface
     */
    protected MerchantCommissionCreatorInterface $merchantCommissionCreator;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionUpdaterInterface
     */
    protected MerchantCommissionUpdaterInterface $merchantCommissionUpdater;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Grouper\MerchantCommissionGrouperInterface $merchantCommissionGrouper
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\MerchantCommissionImportValidatorInterface $merchantCommissionImportValidator
     * @param \Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionCreatorInterface $merchantCommissionCreator
     * @param \Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionUpdaterInterface $merchantCommissionUpdater
     */
    public function __construct(
        MerchantCommissionGrouperInterface $merchantCommissionGrouper,
        MerchantCommissionImportValidatorInterface $merchantCommissionImportValidator,
        MerchantCommissionCreatorInterface $merchantCommissionCreator,
        MerchantCommissionUpdaterInterface $merchantCommissionUpdater
    ) {
        $this->merchantCommissionGrouper = $merchantCommissionGrouper;
        $this->merchantCommissionImportValidator = $merchantCommissionImportValidator;
        $this->merchantCommissionCreator = $merchantCommissionCreator;
        $this->merchantCommissionUpdater = $merchantCommissionUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function importMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer {
        $this->assertRequiredFields($merchantCommissionCollectionRequestTransfer);

        [$newMerchantCommissionTransfers, $existingMerchantCommissionTransfers] = $this->merchantCommissionGrouper->groupMerchantCommissionsByPersistenceExistence(
            $merchantCommissionCollectionRequestTransfer,
        );

        $merchantCommissionCollectionResponseTransfer = $this->merchantCommissionImportValidator->validate(
            $newMerchantCommissionTransfers,
            $existingMerchantCommissionTransfers,
        );

        if ($merchantCommissionCollectionResponseTransfer->getErrors()->count() !== 0) {
            return $merchantCommissionCollectionResponseTransfer->setMerchantCommissions(
                $merchantCommissionCollectionRequestTransfer->getMerchantCommissions(),
            );
        }

        $newMerchantCommissionTransfers = $this->merchantCommissionCreator->createPreValidatedMerchantCommissions(
            $newMerchantCommissionTransfers,
        );
        $existingMerchantCommissionTransfers = $this->merchantCommissionUpdater->updatePreValidatedMerchantCommissions(
            $existingMerchantCommissionTransfers,
        );

        return $merchantCommissionCollectionResponseTransfer->setMerchantCommissions(
            $this->merchantCommissionGrouper->mergeMerchantCommissionTransfers(
                $newMerchantCommissionTransfers,
                $existingMerchantCommissionTransfers,
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer): void
    {
        $merchantCommissionCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireMerchantCommissions();

        foreach ($merchantCommissionCollectionRequestTransfer->getMerchantCommissions() as $merchantCommissionTransfer) {
            $merchantCommissionTransfer
                ->requireKey()
                ->requireName()
                ->requireCalculatorTypePlugin()
                ->requireIsActive()
                ->requireStoreRelation()
                ->requireMerchantCommissionGroup()
                ->getMerchantCommissionGroupOrFail()
                    ->requireKey();

            $this->assertRequiredStoreRelationFields($merchantCommissionTransfer->getStoreRelationOrFail());

            if ($merchantCommissionTransfer->getMerchantCommissionAmounts()->count() !== 0) {
                $this->assertRequiredMerchantCommissionAmountFields($merchantCommissionTransfer->getMerchantCommissionAmounts());
            }

            if ($merchantCommissionTransfer->getMerchants()->count() !== 0) {
                $this->assertRequiredMerchantFields($merchantCommissionTransfer->getMerchants());
            }
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     *
     * @return void
     */
    protected function assertRequiredMerchantCommissionAmountFields(ArrayObject $merchantCommissionAmountTransfers): void
    {
        foreach ($merchantCommissionAmountTransfers as $merchantCommissionAmountTransfer) {
            $merchantCommissionAmountTransfer
                ->requireCurrency()
                ->getCurrencyOrFail()
                    ->requireCode();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function assertRequiredStoreRelationFields(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireStores();

        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeTransfer->requireName();
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return void
     */
    protected function assertRequiredMerchantFields(ArrayObject $merchantTransfers): void
    {
        foreach ($merchantTransfers as $merchantTransfer) {
            $merchantTransfer->requireMerchantReference();
        }
    }
}
