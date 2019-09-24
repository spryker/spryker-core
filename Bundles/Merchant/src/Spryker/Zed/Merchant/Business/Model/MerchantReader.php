<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $merchantRepository;

    /**
     * @var \Spryker\Zed\Merchant\Business\Model\MerchantPluginExecutorInterface
     */
    protected $merchantPluginExecutor;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     * @param \Spryker\Zed\Merchant\Business\Model\MerchantPluginExecutorInterface $merchantPluginExecutor
     */
    public function __construct(
        MerchantRepositoryInterface $merchantRepository,
        MerchantPluginExecutorInterface $merchantPluginExecutor
    ) {
        $this->merchantRepository = $merchantRepository;
        $this->merchantPluginExecutor = $merchantPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer|null $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function find(?MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer = null): MerchantCollectionTransfer
    {
        $merchantCollectionTransfer = $this->merchantRepository->find($merchantCriteriaFilterTransfer);
        $merchantCollectionTransfer = $this->hydrateMerchantCollection($merchantCollectionTransfer);

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findOne(MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer): ?MerchantTransfer
    {
        $merchantTransfer = $this->merchantRepository->findOne($merchantCriteriaFilterTransfer);
        if ($merchantTransfer === null) {
            return null;
        }

        return $this->merchantPluginExecutor->executeMerchantHydratePlugins($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    protected function hydrateMerchantCollection(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        $merchantTransfers = new ArrayObject();
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantTransfer = $this->merchantPluginExecutor->executeMerchantHydratePlugins($merchantTransfer);
            $merchantTransfers->append($merchantTransfer);
        }
        $merchantCollectionTransfer->setMerchants($merchantTransfers);

        return $merchantCollectionTransfer;
    }
}
