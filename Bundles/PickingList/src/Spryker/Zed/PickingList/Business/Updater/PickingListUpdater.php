<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Zed\PickingList\Business\Distinguisher\PickingListDistinguisherInterface;
use Spryker\Zed\PickingList\Business\Filter\PickingListFilterInterface;
use Spryker\Zed\PickingList\Business\StatusGenerator\PickingListStatusGeneratorInterface;
use Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeInterface;
use Spryker\Zed\PickingList\Dependency\External\PickingListToDatabaseConnectionInterface;
use Spryker\Zed\PickingList\Persistence\PickingListEntityManagerInterface;

class PickingListUpdater implements PickingListUpdaterInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Business\Filter\PickingListFilterInterface
     */
    protected PickingListFilterInterface $pickingListFilter;

    /**
     * @var \Spryker\Zed\PickingList\Persistence\PickingListEntityManagerInterface
     */
    protected PickingListEntityManagerInterface $pickingListEntityManager;

    /**
     * @var \Spryker\Zed\PickingList\Business\StatusGenerator\PickingListStatusGeneratorInterface
     */
    protected PickingListStatusGeneratorInterface $pickingListStatusGenerator;

    /**
     * @var \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeInterface
     */
    protected PickingListValidatorCompositeInterface $pickingListValidatorComposite;

    /**
     * @var \Spryker\Zed\PickingList\Dependency\External\PickingListToDatabaseConnectionInterface
     */
    protected PickingListToDatabaseConnectionInterface $databaseConnection;

    /**
     * @var \Spryker\Zed\PickingList\Business\Distinguisher\PickingListDistinguisherInterface
     */
    protected PickingListDistinguisherInterface $pickingListDistinguisher;

    /**
     * @var list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface>
     */
    protected array $pickingListPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\PickingList\Business\Filter\PickingListFilterInterface $pickingListFilter
     * @param \Spryker\Zed\PickingList\Persistence\PickingListEntityManagerInterface $pickingListEntityManager
     * @param \Spryker\Zed\PickingList\Business\StatusGenerator\PickingListStatusGeneratorInterface $pickingListStatusGenerator
     * @param \Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeInterface $pickingListValidatorComposite
     * @param \Spryker\Zed\PickingList\Dependency\External\PickingListToDatabaseConnectionInterface $databaseConnection
     * @param \Spryker\Zed\PickingList\Business\Distinguisher\PickingListDistinguisherInterface $pickingListDistinguisher
     * @param list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface> $pickingListPostUpdatePlugins
     */
    public function __construct(
        PickingListFilterInterface $pickingListFilter,
        PickingListEntityManagerInterface $pickingListEntityManager,
        PickingListStatusGeneratorInterface $pickingListStatusGenerator,
        PickingListValidatorCompositeInterface $pickingListValidatorComposite,
        PickingListToDatabaseConnectionInterface $databaseConnection,
        PickingListDistinguisherInterface $pickingListDistinguisher,
        array $pickingListPostUpdatePlugins
    ) {
        $this->pickingListFilter = $pickingListFilter;
        $this->pickingListEntityManager = $pickingListEntityManager;
        $this->pickingListStatusGenerator = $pickingListStatusGenerator;
        $this->pickingListValidatorComposite = $pickingListValidatorComposite;
        $this->databaseConnection = $databaseConnection;
        $this->pickingListDistinguisher = $pickingListDistinguisher;
        $this->pickingListPostUpdatePlugins = $pickingListPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function updatePickingListCollection(
        PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer {
        $this->assertRequiredPickingListCollectionRequestTransferProperties($pickingListCollectionRequestTransfer);

        $pickingListCollectionResponseTransfer = (new PickingListCollectionResponseTransfer())->setPickingLists(
            $pickingListCollectionRequestTransfer->getPickingLists(),
        );

        $pickingListCollectionResponseTransfer = $this->executePickingListValidation(
            $pickingListCollectionResponseTransfer,
        );

        if ($pickingListCollectionRequestTransfer->getIsTransactional() && count($pickingListCollectionResponseTransfer->getErrors()) > 0) {
            return $pickingListCollectionResponseTransfer;
        }

        $validPickingListCollectionTransfer = $this->pickingListFilter
            ->getValidPickingLists($pickingListCollectionResponseTransfer);

        $invalidPickingListCollectionTransfer = $this->pickingListFilter
            ->getInvalidPickingLists($pickingListCollectionResponseTransfer);

        $persistedPickingListCollectionResponseTransfer = $this->executeUpdatePickingListCollectionTransaction(
            $validPickingListCollectionTransfer,
        );
        if ($persistedPickingListCollectionResponseTransfer->getErrors()->count() > 0) {
            return $pickingListCollectionResponseTransfer->setErrors(
                $persistedPickingListCollectionResponseTransfer->getErrors(),
            );
        }

        $persistedPickingListCollectionTransfer = (new PickingListCollectionTransfer())
            ->setPickingLists($persistedPickingListCollectionResponseTransfer->getPickingLists());
        $pickingListCollectionTransfer = $this->pickingListFilter
            ->mergeValidAndInvalidPickingLists(
                $persistedPickingListCollectionTransfer,
                $invalidPickingListCollectionTransfer,
            );

        return $pickingListCollectionResponseTransfer->setPickingLists(
            $pickingListCollectionTransfer->getPickingLists(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    protected function executePickingListValidation(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): PickingListCollectionResponseTransfer {
        $pickingListCollectionTransfer = (new PickingListCollectionTransfer())->setPickingLists(
            $pickingListCollectionResponseTransfer->getPickingLists(),
        );

        $errorCollectionTransfer = $this->pickingListValidatorComposite->validateCollection(
            $pickingListCollectionTransfer,
        );

        return $pickingListCollectionResponseTransfer->setErrors(
            $errorCollectionTransfer->getErrors(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    protected function executeUpdatePickingListCollectionTransaction(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionResponseTransfer {
        $pickingListCollectionTransfer = $this->updatePickingListStatuses($pickingListCollectionTransfer);
        $this->pickingListDistinguisher->setModifiedAttributes($pickingListCollectionTransfer->getPickingLists());

        $this->databaseConnection->beginTransaction();

        $pickingListTransfers = new ArrayObject();
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $pickingListTransfers->append(
                $this->pickingListEntityManager->updatePickingList($pickingListTransfer),
            );
        }

        $pickingListCollectionResponseTransfer = $this->executePickingListPostUpdatePlugins(
            $pickingListCollectionTransfer->setPickingLists(
                $pickingListTransfers,
            ),
        );
        if ($pickingListCollectionResponseTransfer->getErrors()->count() > 0) {
            $this->databaseConnection->rollBack();

            return $pickingListCollectionResponseTransfer;
        }
        $this->databaseConnection->commit();

        return $pickingListCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function updatePickingListStatuses(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $pickingListStatus = $this->pickingListStatusGenerator->generatePickingListStatus($pickingListTransfer);
            $pickingListTransfer->setStatus($pickingListStatus);
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    protected function executePickingListPostUpdatePlugins(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionResponseTransfer {
        $pickingListCollectionResponseTransfer = (new PickingListCollectionResponseTransfer())
            ->setPickingLists($pickingListCollectionTransfer->getPickingLists());
        foreach ($this->pickingListPostUpdatePlugins as $pickingListPostUpdatePlugin) {
            $pluginPickingListCollectionResponseTransfer = $pickingListPostUpdatePlugin->postUpdate(
                $pickingListCollectionResponseTransfer,
            );
            if ($pluginPickingListCollectionResponseTransfer->getErrors()->count() > 0) {
                return $pluginPickingListCollectionResponseTransfer;
            }
        }

        return $pickingListCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredPickingListCollectionRequestTransferProperties(
        PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
    ): void {
        $pickingListCollectionRequestTransfer->requireIsTransactional()
            ->requirePickingLists();

        foreach ($pickingListCollectionRequestTransfer->getPickingLists() as $pickingListTransfer) {
            $this->assertRequiredPickingListTransferProperties($pickingListTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return void
     */
    protected function assertRequiredPickingListTransferProperties(
        PickingListTransfer $pickingListTransfer
    ): void {
        $pickingListTransfer->requireUuid();

        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $this->assertRequiredPickingListItemTransferProperties($pickingListItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     *
     * @return void
     */
    protected function assertRequiredPickingListItemTransferProperties(
        PickingListItemTransfer $pickingListItemTransfer
    ): void {
        $pickingListItemTransfer->requireUuid()
            ->requireNumberOfNotPicked()
            ->requireNumberOfPicked();
    }
}
