<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateSlotUpdater implements ConfigurableBundleTemplateSlotUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface
     */
    protected $configurableBundleSlotTranslationWriter;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface
     */
    protected $configurableBundleNameGenerator;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface
     */
    protected $productListWriter;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface
     */
    protected $configurableBundleTemplateSlotReader;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface $configurableBundleSlotTranslationWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface $productListWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface $configurableBundleTemplateSlotReader
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTranslationWriterInterface $configurableBundleSlotTranslationWriter,
        ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator,
        ProductListWriterInterface $productListWriter,
        ConfigurableBundleTemplateSlotReaderInterface $configurableBundleTemplateSlotReader
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleSlotTranslationWriter = $configurableBundleSlotTranslationWriter;
        $this->configurableBundleNameGenerator = $configurableBundleNameGenerator;
        $this->productListWriter = $productListWriter;
        $this->configurableBundleTemplateSlotReader = $configurableBundleTemplateSlotReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        $configurableBundleTemplateSlotResponseTransfer = $this->getConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        if (!$configurableBundleTemplateSlotResponseTransfer->getIsSuccessful()) {
            return $this->getErrorResponse($configurableBundleTemplateSlotResponseTransfer->getMessages());
        }

        $configurableBundleTemplateSlotTransfer
            ->setName($this->configurableBundleNameGenerator->generateTemplateSlotName($configurableBundleTemplateSlotTransfer))
            ->setConfigurableBundleTemplate(
                $configurableBundleTemplateSlotResponseTransfer->getConfigurableBundleTemplateSlot()->getConfigurableBundleTemplate()
            );

        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateSlotTransfer) {
            return $this->executeUpdateConfigurableBundleTemplateSlotTransaction($configurableBundleTemplateSlotTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    protected function executeUpdateConfigurableBundleTemplateSlotTransaction(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        $productListResponseTransfer = $this->productListWriter->updateProductList($configurableBundleTemplateSlotTransfer);

        if (!$productListResponseTransfer->getIsSuccessful()) {
            return $this->getErrorResponse($productListResponseTransfer->getMessages());
        }

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleEntityManager
            ->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        $this->configurableBundleSlotTranslationWriter->saveTemplateSlotTranslations($configurableBundleTemplateSlotTransfer);

        return (new ConfigurableBundleTemplateSlotResponseTransfer())
            ->setConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    protected function getConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        $configurableBundleTemplateSlotTransfer->requireIdConfigurableBundleTemplateSlot();

        $configurableBundleTemplateSlotResponseTransfer = $this->configurableBundleTemplateSlotReader
            ->getConfigurableBundleTemplateSlotById($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot());

        return $configurableBundleTemplateSlotResponseTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    protected function getErrorResponse(ArrayObject $messageTransfers): ConfigurableBundleTemplateSlotResponseTransfer
    {
        return (new ConfigurableBundleTemplateSlotResponseTransfer())
            ->setMessages($messageTransfers)
            ->setIsSuccessful(false);
    }
}
