<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Orm\Zed\StoreContext\Persistence\SpyStoreContext;
use Spryker\Zed\StoreContext\Dependency\Service\StoreContextToUtilEncodingServiceInterface;

class StoreContextMapper
{
    /**
     * @var \Spryker\Zed\StoreContext\Dependency\Service\StoreContextToUtilEncodingServiceInterface $utilEncodingService
     */
    protected StoreContextToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\StoreContext\Dependency\Service\StoreContextToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(StoreContextToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Orm\Zed\StoreContext\Persistence\SpyStoreContext $storeContextEntity
     * @param \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer $storeApplicationContextCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer
     */
    public function mapStoreContextEntityToStoreApplicationContextCollectionTransfer(
        SpyStoreContext $storeContextEntity,
        StoreApplicationContextCollectionTransfer $storeApplicationContextCollectionTransfer
    ): StoreApplicationContextCollectionTransfer {
        $storeApplicationContextCollectionData = $this->utilEncodingService->decodeJson($storeContextEntity->getApplicationContextCollection(), true);

        if ($storeApplicationContextCollectionData === null || !is_array($storeApplicationContextCollectionData)) {
            return $storeApplicationContextCollectionTransfer;
        }

        $storeApplicationContextCollectionTransfer->fromArray($storeApplicationContextCollectionData);

        return $storeApplicationContextCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextTransfer $storeContextTransfer
     * @param \Orm\Zed\StoreContext\Persistence\SpyStoreContext $storeContextEntity
     *
     * @return \Orm\Zed\StoreContext\Persistence\SpyStoreContext
     */
    public function mapStoreContextTransferToStoreContextEntity(
        StoreContextTransfer $storeContextTransfer,
        SpyStoreContext $storeContextEntity
    ): SpyStoreContext {
        $storeContextEntity->setFkStore($storeContextTransfer->getStoreOrFail()->getIdStoreOrFail());
        $storeContextEntity->setApplicationContextCollection(
            $this->utilEncodingService->encodeJson($storeContextTransfer->getApplicationContextCollectionOrFail()->toArray()) ?? '{}',
        );

        return $storeContextEntity;
    }

    /**
     * @param \Orm\Zed\StoreContext\Persistence\SpyStoreContext $storeContextEntity
     * @param \Generated\Shared\Transfer\StoreContextTransfer $storeContextTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextTransfer
     */
    public function mapStoreContextEntityToStoreContextTransfer(
        SpyStoreContext $storeContextEntity,
        StoreContextTransfer $storeContextTransfer
    ): StoreContextTransfer {
        $applicationContextCollection = $this->utilEncodingService->decodeJson($storeContextEntity->getApplicationContextCollection(), true);

        if (!is_array($applicationContextCollection)) {
            return $storeContextTransfer;
        }

        $storeContextTransfer->setApplicationContextCollection(
            (new StoreApplicationContextCollectionTransfer())->fromArray($applicationContextCollection),
        );

        return $storeContextTransfer;
    }
}
