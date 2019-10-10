<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateSlotWriter implements ConfigurableBundleTemplateSlotWriterInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_FOUND = 'Configurable bundle template slot with id "%id%" was not found.';
    protected const ERROR_MESSAGE_PARAM_ID = '%id%';
    protected const ERROR_MESSAGE_TYPE = 'error';

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
    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateSlotTransfer) {
            return $this->executeUpdateConfigurableBundleTemplateSlotTransaction($configurableBundleTemplateSlotTransfer);
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
                $productListResponseTransfer->getMessages()
            );
        }

        $configurableBundleTemplateSlotTransfer->setProductList($productListResponseTransfer->getProductList());

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleNameGenerator
            ->setConfigurableBundleTemplateSlotName($configurableBundleTemplateSlotTransfer);

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleEntityManager
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
        $this->configurableBundleTemplateSlotTranslationWriter->saveTranslations($configurableBundleTemplateSlotTransfer);

        return $this->createConfigurableBundleResponseTransfer($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    protected function executeUpdateConfigurableBundleTemplateSlotTransaction(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer {
        $configurableBundleTemplateSlotTransfer->requireFkConfigurableBundleTemplate()
            ->requireProductList()
            ->getProductList()
            ->requireIdProductList();

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleNameGenerator
            ->setConfigurableBundleTemplateSlotName($configurableBundleTemplateSlotTransfer);

        $this->productListWriter->updateProductListForConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        if (!$this->configurableBundleEntityManager->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer)) {
            $messageTransfer = (new MessageTransfer())
                ->setValue(static::ERROR_MESSAGE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_FOUND)
                ->setType(static::ERROR_MESSAGE_TYPE)
                ->setParameters([
                    static::ERROR_MESSAGE_PARAM_ID => $configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot(),
                ]);

            return $this->createConfigurableBundleResponseTransfer(
                $configurableBundleTemplateSlotTransfer,
                new ArrayObject([$messageTransfer])
            );
        }

        $this->configurableBundleTemplateSlotTranslationWriter->saveTranslations($configurableBundleTemplateSlotTransfer);

        return $this->createConfigurableBundleResponseTransfer($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[]|null $messageTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    protected function createConfigurableBundleResponseTransfer(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        ?ArrayObject $messageTransfers = null
    ): ConfigurableBundleResponseTransfer {
        $configurableBundleResponseTransfer = (new ConfigurableBundleResponseTransfer())
            ->setConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer)
            ->setIsSuccessful($messageTransfers === null || !$messageTransfers->count());

        if ($configurableBundleResponseTransfer->getIsSuccessful()) {
            return $configurableBundleResponseTransfer;
        }

        foreach ($messageTransfers as $messageTransfer) {
            $configurableBundleResponseTransfer->addMessage($messageTransfer);
        }

        return $configurableBundleResponseTransfer;
    }
}
