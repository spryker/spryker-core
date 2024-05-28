<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Exporter;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantExportCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToStoreFacadeInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;
use Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException;

/**
 * @deprecated Will be removed without replacement.
 */
class MerchantExporter implements MerchantExporterInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface
     */
    protected MerchantToEventFacadeInterface $eventFacade;

    /**
     * @var \Spryker\Zed\Merchant\Dependency\Facade\MerchantToStoreFacadeInterface
     */
    protected MerchantToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected MerchantRepositoryInterface $merchantRepository;

    /**
     * @param \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Merchant\Dependency\Facade\MerchantToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     */
    public function __construct(
        MerchantToEventFacadeInterface $eventFacade,
        MerchantToStoreFacadeInterface $storeFacade,
        MerchantRepositoryInterface $merchantRepository
    ) {
        $this->eventFacade = $eventFacade;
        $this->storeFacade = $storeFacade;
        $this->merchantRepository = $merchantRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer
     *
     * @return void
     */
    public function export(MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer): void
    {
        if (!$this->isMerchantExportCriteriaStoreReferenceValid($merchantExportCriteriaTransfer)) {
            return;
        }

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer());
        $storeTransfer = $this->findStoreForExporting($merchantExportCriteriaTransfer);

        if ($storeTransfer) {
            $merchantCriteriaTransfer->setStore($storeTransfer);
        }

        $merchantCollectionTransfer = $this->merchantRepository->get($merchantCriteriaTransfer);

        $merchantIds = [];

        foreach ($merchantCollectionTransfer->getMerchants() as $merchant) {
            $merchantIds[] = $merchant->getIdMerchantOrFail();
        }

        $eventEntityTransfers = $this->createEventTransfers($merchantIds);

        $this->eventFacade->triggerBulk(MerchantEvents::MERCHANT_EXPORTED, $eventEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer
     *
     * @return bool
     */
    protected function isMerchantExportCriteriaStoreReferenceValid(
        MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer
    ): bool {
        if (!$merchantExportCriteriaTransfer->isPropertyModified(MerchantExportCriteriaTransfer::STORE_REFERENCE)) {
            return true;
        }

        try {
            $this->getStoreTransfer($merchantExportCriteriaTransfer);
        } catch (StoreReferenceNotFoundException | NullValueException $exception) {
            $this->getLogger()->error(
                sprintf('Failed to getStoreTransfer with message %s', $exception->getMessage()),
                ['exception' => $exception],
            );

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    protected function findStoreForExporting(
        MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer
    ): ?StoreTransfer {
        if (!$merchantExportCriteriaTransfer->isPropertyModified(MerchantExportCriteriaTransfer::STORE_REFERENCE)) {
            return null;
        }

        return $this->getStoreTransfer($merchantExportCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransfer(MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer): StoreTransfer
    {
        return $this->storeFacade->getStoreByStoreReference($merchantExportCriteriaTransfer->getStoreReferenceOrFail());
    }

    /**
     * @param array<int> $merchantIds
     *
     * @return array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected function createEventTransfers(array $merchantIds): array
    {
        $result = [];

        foreach ($merchantIds as $merchantId) {
            $result[] = (new EventEntityTransfer())->setId($merchantId);
        }

        return $result;
    }
}
