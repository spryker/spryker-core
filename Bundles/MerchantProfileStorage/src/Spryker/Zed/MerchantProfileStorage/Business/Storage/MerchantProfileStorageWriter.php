<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Business\Storage;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
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
     * @var \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface $repository
     * @param \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToMerchantProfileFacadeInterface $merchantProfileFacade
     * @param \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig $config
     */
    public function __construct(
        MerchantProfileStorageRepositoryInterface $repository,
        MerchantProfileStorageEntityManagerInterface $entityManager,
        MerchantProfileStorageToMerchantProfileFacadeInterface $merchantProfileFacade,
        MerchantProfileStorageConfig $config
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->merchantProfileFacade = $merchantProfileFacade;
        $this->config = $config;
    }

    /**
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function publish(array $merchantProfileIds): void
    {
        $merchantProfileCollectionTransfer = $this->merchantProfileFacade->find(
            (new MerchantProfileCriteriaFilterTransfer())
                ->setMerchantProfileIds($merchantProfileIds)
        );

        $merchantProfileTransfers = $merchantProfileCollectionTransfer->getMerchantProfiles();

        foreach ($merchantProfileTransfers as $merchantProfileTransfer) {
            if (!$merchantProfileTransfer->getIsActive()) {
                $this->entityManager->deleteMerchantProfileStorageEntitiesByMerchantIds([
                    $merchantProfileTransfer->getIdMerchantProfile(),
                ]);

                continue;
            }

            $this->entityManager->saveMerchantProfileStorage($merchantProfileTransfer);
        }
    }

    /**
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function unpublish(array $merchantProfileIds): void
    {
        $merchantProfileCollectionTransfer = $this->merchantProfileFacade->find(
            (new MerchantProfileCriteriaFilterTransfer())
                ->setMerchantIds($merchantProfileIds)
        );

        $merchantProfileTransfers = $merchantProfileCollectionTransfer->getMerchantProfiles();

        $merchantProfileIds = array_map(function (MerchantProfileTransfer $merchantProfileTransfer) {
            return $merchantProfileTransfer->getFkMerchant();
        }, $merchantProfileTransfers->getArrayCopy());

        $this->entityManager->deleteMerchantProfileStorageEntitiesByMerchantIds($merchantProfileIds);
    }
}
