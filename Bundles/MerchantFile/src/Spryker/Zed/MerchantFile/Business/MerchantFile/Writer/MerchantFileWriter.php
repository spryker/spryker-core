<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business\MerchantFile\Writer;

use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantFile\Persistence\MerchantFileEntityManagerInterface;

class MerchantFileWriter implements MerchantFileWriterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\MerchantFile\Persistence\MerchantFileEntityManagerInterface $entityManager
     * @param array<\Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFilePostSavePluginInterface> $merchantFilePostSavePlugins
     */
    public function __construct(
        protected MerchantFileEntityManagerInterface $entityManager,
        protected array $merchantFilePostSavePlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function saveMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer
    {
        $this->assertMerchantFileTransfer($merchantFileTransfer);

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($merchantFileTransfer): MerchantFileTransfer {
                return $this->doSaveMerchantFile($merchantFileTransfer);
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    protected function doSaveMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer
    {
        $merchantFileTransfer = $this->entityManager->saveMerchantFile($merchantFileTransfer);
        $merchantFileTransfer = $this->executePostSavePlugins($merchantFileTransfer);

        return $merchantFileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    protected function executePostSavePlugins(MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer
    {
        foreach ($this->merchantFilePostSavePlugins as $merchantFilePostSavePlugin) {
            $merchantFileTransfer = $merchantFilePostSavePlugin->execute($merchantFileTransfer);
        }

        return $merchantFileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return void
     */
    protected function assertMerchantFileTransfer(MerchantFileTransfer $merchantFileTransfer): void
    {
        $merchantFileTransfer
            ->requireFkMerchant()
            ->requireFkUser()
            ->requireType()
            ->requireUploadedUrl();
    }
}
