<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\SpyMerchantEntityTransfer;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantWriter implements MerchantWriterInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Merchant\Business\Model\MerchantKeyGeneratorInterface
     */
    protected $keyGenerator;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Merchant\Business\Model\MerchantKeyGeneratorInterface $keyGenerator
     */
    public function __construct(MerchantEntityManagerInterface $entityManager, MerchantKeyGeneratorInterface $keyGenerator)
    {
        $this->entityManager = $entityManager;
        $this->keyGenerator = $keyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function create(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer->requireName();

        if (!$merchantTransfer->getMerchantKey()) {
            $key = $this->keyGenerator->generateUniqueKey($merchantTransfer->getName());
            $merchantTransfer->setMerchantKey($key);
        }

        $merchantEntityTransfer = (new SpyMerchantEntityTransfer())
            ->setName($merchantTransfer->getName())
            ->setMerchantKey($merchantTransfer->getMerchantKey());

        $merchantEntityTransfer = $this->entityManager->saveMerchant($merchantEntityTransfer);

        return (new MerchantTransfer())
            ->setIdMerchant($merchantEntityTransfer->getIdMerchant())
            ->setName($merchantEntityTransfer->getName())
            ->setMerchantKey($merchantEntityTransfer->getMerchantKey());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function update(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer
            ->requireIdMerchant()
            ->requireName();

        $merchantEntityTransfer = (new SpyMerchantEntityTransfer())
            ->setIdMerchant($merchantTransfer->getIdMerchant())
            ->setName($merchantTransfer->getName());

        if ($merchantTransfer->getMerchantKey()) {
            $merchantEntityTransfer->setMerchantKey($merchantTransfer->getMerchantKey());
        }

        $merchantEntityTransfer = $this->entityManager->saveMerchant($merchantEntityTransfer);

        return (new MerchantTransfer())
            ->setIdMerchant($merchantEntityTransfer->getIdMerchant())
            ->setName($merchantEntityTransfer->getName())
            ->setMerchantKey($merchantEntityTransfer->getMerchantKey());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function delete(MerchantTransfer $merchantTransfer): void
    {
        $merchantTransfer->requireIdMerchant();

        $this->entityManager->deleteMerchantById($merchantTransfer->getIdMerchant());
    }
}
