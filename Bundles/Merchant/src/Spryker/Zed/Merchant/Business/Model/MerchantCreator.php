<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface;
use Spryker\Zed\Merchant\Business\MerchantAddress\MerchantAddressWriterInterface;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantCreator implements MerchantCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $merchantEntityManager;

    /**
     * @var \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface
     */
    protected $merchantKeyGenerator;

    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantAddress\MerchantAddressWriterInterface
     */
    protected $merchantAddressWriter;

    /**
     * @var \Spryker\Zed\Merchant\MerchantConfig
     */
    protected $merchantConfig;

    /**
     * @var \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface[]
     */
    protected $merchantPostSavePlugins;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $merchantEntityManager
     * @param \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface $merchantKeyGenerator
     * @param \Spryker\Zed\Merchant\Business\MerchantAddress\MerchantAddressWriterInterface $merchantAddressWriter
     * @param \Spryker\Zed\Merchant\MerchantConfig $merchantConfig
     * @param \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface[] $merchantPostSavePlugins
     */
    public function __construct(
        MerchantEntityManagerInterface $merchantEntityManager,
        MerchantKeyGeneratorInterface $merchantKeyGenerator,
        MerchantAddressWriterInterface $merchantAddressWriter,
        MerchantConfig $merchantConfig,
        array $merchantPostSavePlugins
    ) {
        $this->merchantEntityManager = $merchantEntityManager;
        $this->merchantKeyGenerator = $merchantKeyGenerator;
        $this->merchantAddressWriter = $merchantAddressWriter;
        $this->merchantConfig = $merchantConfig;
        $this->merchantPostSavePlugins = $merchantPostSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function create(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $this->assertDefaultMerchantRequirements($merchantTransfer);

        if (empty($merchantTransfer->getMerchantKey())) {
            $merchantTransfer->setMerchantKey(
                $this->merchantKeyGenerator->generateMerchantKey($merchantTransfer->getName())
            );
        }

        $merchantTransfer->setStatus($this->merchantConfig->getDefaultMerchantStatus());

        $merchantTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
            return $this->executeCreateTransaction($merchantTransfer);
        });

        $merchantResponseTransfer = $this->createMerchantResponseTransfer();
        $merchantResponseTransfer = $merchantResponseTransfer
            ->setIsSuccess(true)
            ->setMerchant($merchantTransfer);

        return $merchantResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeCreateTransaction(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantAddressCollectionTransfer = $merchantTransfer->getAddressCollection();
        $merchantTransfer = $this->merchantEntityManager->saveMerchant($merchantTransfer);
        $merchantAddressCollectionTransfer = $this->merchantAddressWriter->saveMerchantAddressCollection(
            $merchantAddressCollectionTransfer,
            $merchantTransfer->getIdMerchant()
        );
        $merchantTransfer->setAddressCollection($merchantAddressCollectionTransfer);
        $merchantTransfer = $this->executeMerchantPostSavePlugins($merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function createMerchantResponseTransfer(): MerchantResponseTransfer
    {
        return (new MerchantResponseTransfer())
            ->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeMerchantPostSavePlugins(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        foreach ($this->merchantPostSavePlugins as $merchantPostSavePlugin) {
            $merchantTransfer = $merchantPostSavePlugin->execute($merchantTransfer);
        }

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function assertDefaultMerchantRequirements(MerchantTransfer $merchantTransfer): void
    {
        $merchantTransfer
            ->requireName()
            ->requireEmail();
    }
}
