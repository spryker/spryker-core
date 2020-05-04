<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $merchantRepository;

    /**
     * @var \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface[]
     */
    protected $merchantExpanderPlugins;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     * @param \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface[] $merchantExpanderPlugins
     */
    public function __construct(
        MerchantRepositoryInterface $merchantRepository,
        array $merchantExpanderPlugins
    ) {
        $this->merchantRepository = $merchantRepository;
        $this->merchantExpanderPlugins = $merchantExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): MerchantCollectionTransfer
    {
        $merchantCollectionTransfer = $this->merchantRepository->get($merchantCriteriaTransfer);
        $merchantCollectionTransfer = $this->expandMerchantCollection($merchantCollectionTransfer);

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findOne(MerchantCriteriaTransfer $merchantCriteriaTransfer): ?MerchantTransfer
    {
        $merchantTransfer = $this->merchantRepository->findOne($merchantCriteriaTransfer);
        if ($merchantTransfer === null) {
            return null;
        }

        return $this->expandMerchant($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    protected function expandMerchantCollection(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        $merchantIds = $this->getMerchantIds($merchantCollectionTransfer);
        $merchantStoreRelationTransferMap = $this->merchantRepository->getMerchantStoreRelationMapByMerchantIds($merchantIds);
        $merchantUrlTransfersMap = $this->merchantRepository->getUrlsMapByMerchantIds($merchantIds);

        $merchantTransfers = new ArrayObject();
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantTransfer->setStoreRelation($merchantStoreRelationTransferMap[$merchantTransfer->getIdMerchant()]);
            $merchantTransfer->setUrlCollection(new ArrayObject($merchantUrlTransfersMap[$merchantTransfer->getIdMerchant()] ?? []));

            $merchantTransfer = $this->executeMerchantExpanderPlugins($merchantTransfer);
            $merchantTransfers->append($merchantTransfer);
        }
        $merchantCollectionTransfer->setMerchants($merchantTransfers);

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function expandMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantStoreRelationTransferMap = $this->merchantRepository->getMerchantStoreRelationMapByMerchantIds([$merchantTransfer->getIdMerchant()]);
        $merchantUrlTransfersMap = $this->merchantRepository->getUrlsMapByMerchantIds([$merchantTransfer->getIdMerchant()]);

        $merchantTransfer->setStoreRelation($merchantStoreRelationTransferMap[$merchantTransfer->getIdMerchant()]);
        $merchantTransfer->setUrlCollection(new ArrayObject($merchantUrlTransfersMap[$merchantTransfer->getIdMerchant()] ?? []));

        return $this->executeMerchantExpanderPlugins($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeMerchantExpanderPlugins(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        foreach ($this->merchantExpanderPlugins as $merchantExpanderPlugin) {
            $merchantTransfer = $merchantExpanderPlugin->expand($merchantTransfer);
        }

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return int[]
     */
    protected function getMerchantIds(MerchantCollectionTransfer $merchantCollectionTransfer): array
    {
        return array_map(function (MerchantTransfer $merchantTransfer): int {
            return $merchantTransfer->getIdMerchant();
        }, $merchantCollectionTransfer->getMerchants()->getArrayCopy());
    }
}
