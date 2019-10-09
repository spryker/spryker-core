<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Business\Storage;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Generated\Shared\Transfer\MerchantProfileViewTransfer;
use Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToMerchantProfileFacadeInterface;
use Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig;
use Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageEntityManagerInterface;
use Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface;

class MerchantProfileStorageWriter implements MerchantProfileStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToMerchantProfileFacadeInterface
     */
    protected $merchantProfileFacade;

    /**
     * @var \Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface $repository
     * @param \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToMerchantProfileFacadeInterface $merchantProfileFacade
     * @param \Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig $config
     */
    public function __construct(
        MerchantProfileStorageRepositoryInterface $repository,
        MerchantProfileStorageEntityManagerInterface $entityManager,
        MerchantProfileStorageToMerchantProfileFacadeInterface $merchantProfileFacade,
        MerchantProfileStorageToLocaleFacadeInterface $localeFacade,
        MerchantProfileStorageConfig $config
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->merchantProfileFacade = $merchantProfileFacade;
        $this->localeFacade = $localeFacade;
        $this->config = $config;
    }

    /**
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function publish(array $merchantProfileIds): void
    {
        $merchantProfileCriteriaFilterTransfer = $this->createMerchantProfileCriteriaFilterTransfer($merchantProfileIds);

        $merchantProfileCollectionTransfer = $this->merchantProfileFacade->find($merchantProfileCriteriaFilterTransfer);
        $merchantProfileTransfers = $merchantProfileCollectionTransfer->getMerchantProfiles();

        foreach ($merchantProfileTransfers as $merchantProfileTransfer) {
            $merchantProfileViewTransfer = new MerchantProfileViewTransfer();
            $merchantProfileViewTransfer->fromArray($merchantProfileTransfer->toArray(), true);

            $merchantProfileStorageTransfer = new MerchantProfileStorageTransfer();
            $merchantProfileStorageTransfer->setFkMerchant($merchantProfileTransfer->getFkMerchant());
            $merchantProfileStorageTransfer->setFkMerchantProfile($merchantProfileTransfer->getIdMerchantProfile());
            $merchantProfileStorageTransfer->setData($merchantProfileViewTransfer);
            $merchantProfileStorageTransfer->setIsSendingToQueue($this->config->isSendingToQueue());

            $this->entityManager->saveMerchantProfileStorageEntity($merchantProfileStorageTransfer);
        }
    }

    /**
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function unpublish(array $merchantProfileIds): void
    {
        $merchantProfileCriteriaFilterTransfer = $this->createMerchantProfileCriteriaFilterTransfer($merchantProfileIds, false);
        $merchantProfileCollectionTransfer = $this->merchantProfileFacade->find($merchantProfileCriteriaFilterTransfer);
        $merchantProfileTransfers = $merchantProfileCollectionTransfer->getMerchantProfiles();
        $merchantProfileIds = array_map(function ($merchantProfileTransfer) {
            return $merchantProfileTransfer->getIdMerchantProfile();
        }, $merchantProfileTransfers->getArrayCopy());

        $this->entityManager->deleteMerchantProfileStorageEntitiesByMerchantProfileIds($merchantProfileIds);
    }

    /**
     * @param array $merchantProfileIds
     * @param bool $active
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer
     */
    protected function createMerchantProfileCriteriaFilterTransfer(array $merchantProfileIds, bool $active = true): MerchantProfileCriteriaFilterTransfer
    {
        $merchantCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setMerchantProfileIds($merchantProfileIds);
        $merchantCriteriaFilterTransfer->setIsActive($active);

        return $merchantCriteriaFilterTransfer;
    }
}
