<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Business\Address\MerchantAddressWriterInterface;
use Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantWriter implements MerchantWriterInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface
     *
     */
    protected $merchantKeyGenerator;

    /**
     * @var \Spryker\Zed\Merchant\Business\Address\MerchantAddressWriterInterface
     */
    protected $merchantAddressWriter;

    /**
     * @var \Spryker\Zed\Merchant\MerchantConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface $merchantKeyGenerator
     * @param \Spryker\Zed\Merchant\Business\Address\MerchantAddressWriterInterface $merchantAddressWriter
     * @param \Spryker\Zed\Merchant\MerchantConfig $config
     */
    public function __construct(
        MerchantEntityManagerInterface $entityManager,
        MerchantKeyGeneratorInterface $merchantKeyGenerator,
        MerchantAddressWriterInterface $merchantAddressWriter,
        MerchantConfig $config
    ) {
        $this->entityManager = $entityManager;
        $this->merchantKeyGenerator = $merchantKeyGenerator;
        $this->merchantAddressWriter = $merchantAddressWriter;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function create(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer
            ->requireName()
            ->requireRegistrationNumber()
            ->requireContactPersonTitle()
            ->requireContactPersonFirstName()
            ->requireContactPersonLastName()
            ->requireContactPersonPhone()
            ->requireAddress();

        if (empty($merchantTransfer->getMerchantKey())) {
            $merchantTransfer->setMerchantKey(
                $this->merchantKeyGenerator->generateMerchantKey($merchantTransfer->getName())
            );
        }

        $merchantTransfer->setStatus($this->config->getDefaultMerchantStatus());

        $merchantTransfer = $this->entityManager->saveMerchant($merchantTransfer);

        $merchantAddressTransfer = $merchantTransfer->getAddress();
        $merchantAddressTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        $this->merchantAddressWriter->create($merchantAddressTransfer);

        return $merchantTransfer;
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
            ->requireName()
            ->requireRegistrationNumber()
            ->requireContactPersonTitle()
            ->requireContactPersonFirstName()
            ->requireContactPersonLastName()
            ->requireContactPersonPhone()
            ->requireAddress();

        if (empty($merchantTransfer->getMerchantKey())) {
            $merchantTransfer->setMerchantKey(
                $this->merchantKeyGenerator->generateMerchantKey($merchantTransfer->getName())
            );
        }

        $merchantAddressTransfer = $merchantTransfer->getAddress();
        $merchantAddressTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        $this->merchantAddressWriter->update($merchantAddressTransfer);

        return $this->entityManager->saveMerchant($merchantTransfer);
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
