<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateSlotWriter implements ConfigurableBundleTemplateSlotWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateSlotTranslationWriterInterface
     */
    protected $configurableBundleTemplateSlotTranslationWriter;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface
     */
    protected $configurableBundleNameGenerator;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface
     */
    protected $productListWriter;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateSlotTranslationWriterInterface $configurableBundleTemplateSlotTranslationWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface $productListWriter
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTemplateSlotTranslationWriterInterface $configurableBundleTemplateSlotTranslationWriter,
        ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator,
        ProductListWriterInterface $productListWriter
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleTemplateSlotTranslationWriter = $configurableBundleTemplateSlotTranslationWriter;
        $this->configurableBundleNameGenerator = $configurableBundleNameGenerator;
        $this->productListWriter = $productListWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateSlotTransfer) {
            return $this->executeCreateConfigurableBundleTemplateSlotTransaction($configurableBundleTemplateSlotTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    protected function executeCreateConfigurableBundleTemplateSlotTransaction(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer {
        $configurableBundleTemplateSlotTransfer->requireFkConfigurableBundleTemplate();

        $productListResponseTransfer = $this->productListWriter->createProductListForConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        if (!$productListResponseTransfer->getIsSuccessful()) {
            return $this->createConfigurableBundleResponseTransfer(
                $configurableBundleTemplateSlotTransfer,
                $productListResponseTransfer->getMessages()[0]
            );
        }

        $configurableBundleTemplateSlotTransfer->setFkProductList(
            $productListResponseTransfer->getProductList()->getIdProductList()
        );

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleNameGenerator
            ->setConfigurableBundleTemplateSlotName($configurableBundleTemplateSlotTransfer);

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleEntityManager
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
        $this->configurableBundleTemplateSlotTranslationWriter->saveTranslations($configurableBundleTemplateSlotTransfer);

        return $this->createConfigurableBundleResponseTransfer($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param \Generated\Shared\Transfer\MessageTransfer|null $messageTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    protected function createConfigurableBundleResponseTransfer(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        ?MessageTransfer $messageTransfer = null
    ): ConfigurableBundleResponseTransfer {
        $configurableBundleResponseTransfer = (new ConfigurableBundleResponseTransfer())
            ->setConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer)
            ->setIsSuccessful($messageTransfer === null);

        if ($configurableBundleResponseTransfer->getIsSuccessful()) {
            return $configurableBundleResponseTransfer;
        }

        return $configurableBundleResponseTransfer->addMessage($messageTransfer);
    }
}
