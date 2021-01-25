<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateSlotCreator implements ConfigurableBundleTemplateSlotCreatorInterface
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
     * @var \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface
     */
    protected $configurableBundleTemplateReader;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface $configurableBundleSlotTranslationWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface $productListWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTranslationWriterInterface $configurableBundleSlotTranslationWriter,
        ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator,
        ProductListWriterInterface $productListWriter,
        ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleSlotTranslationWriter = $configurableBundleSlotTranslationWriter;
        $this->configurableBundleNameGenerator = $configurableBundleNameGenerator;
        $this->productListWriter = $productListWriter;
        $this->configurableBundleTemplateReader = $configurableBundleTemplateReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        $configurableBundleTemplateResponseTransfer = $this->getConfigurableBundleTemplate($configurableBundleTemplateSlotTransfer);

        if (!$configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            return $this->getErrorResponse($configurableBundleTemplateResponseTransfer->getMessages());
        }

        $configurableBundleTemplateSlotTransfer->setConfigurableBundleTemplate(
            $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate()
        );

        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateSlotTransfer) {
            return $this->executeCreateConfigurableBundleTemplateSlotTransaction($configurableBundleTemplateSlotTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    protected function executeCreateConfigurableBundleTemplateSlotTransaction(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        $productListResponseTransfer = $this->productListWriter->createProductList($configurableBundleTemplateSlotTransfer);

        if (!$productListResponseTransfer->getIsSuccessful()) {
            return $this->getErrorResponse($productListResponseTransfer->getMessages());
        }

        $configurableBundleTemplateSlotTransfer
            ->setProductList($productListResponseTransfer->getProductList())
            ->setName($this->configurableBundleNameGenerator->generateTemplateSlotName($configurableBundleTemplateSlotTransfer));

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleEntityManager
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        $this->configurableBundleSlotTranslationWriter->saveTemplateSlotTranslations($configurableBundleTemplateSlotTransfer);

        return (new ConfigurableBundleTemplateSlotResponseTransfer())
            ->setConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function getConfigurableBundleTemplate(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateSlotTransfer->requireFkConfigurableBundleTemplate();

        $configurableBundleTemplateResponseTransfer = $this->configurableBundleTemplateReader
            ->getConfigurableBundleTemplateById($configurableBundleTemplateSlotTransfer->getFkConfigurableBundleTemplate());

        return $configurableBundleTemplateResponseTransfer;
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
