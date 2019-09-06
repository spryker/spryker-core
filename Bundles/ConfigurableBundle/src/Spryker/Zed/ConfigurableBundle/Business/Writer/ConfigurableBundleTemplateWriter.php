<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateWriter implements ConfigurableBundleTemplateWriterInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = 'Configurable bundle template with id "%id%" was not found.';
    protected const ERROR_MESSAGE_PARAM_ID = '%id%';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface
     */
    protected $configurableBundleTemplateTranslationWriter;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface
     */
    protected $configurableBundleTemplateNameGenerator;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface $configurableBundleTemplateTranslationWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface $configurableBundleTemplateNameGenerator
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTemplateTranslationWriterInterface $configurableBundleTemplateTranslationWriter,
        ConfigurableBundleTemplateNameGeneratorInterface $configurableBundleTemplateNameGenerator
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleTemplateTranslationWriter = $configurableBundleTemplateTranslationWriter;
        $this->configurableBundleTemplateNameGenerator = $configurableBundleTemplateNameGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateTransfer) {
            return $this->executeCreateConfigurableBundleTemplateTransaction($configurableBundleTemplateTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateTransfer) {
            return $this->executeUpdateConfigurableBundleTemplateTransaction($configurableBundleTemplateTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function executeCreateConfigurableBundleTemplateTransaction(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateTransfer = $this->configurableBundleTemplateNameGenerator->generateConfigurableBundleTemplateName($configurableBundleTemplateTransfer);

        $this->configurableBundleEntityManager->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);
        $this->configurableBundleTemplateTranslationWriter->saveTranslations($configurableBundleTemplateTransfer);

        return $this->createConfigurableBundleTemplateResponseTransfer($configurableBundleTemplateTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function executeUpdateConfigurableBundleTemplateTransaction(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $hasTranslations = $configurableBundleTemplateTransfer->getTranslations()
            ? $configurableBundleTemplateTransfer->getTranslations()->count()
            : false;

        if ($hasTranslations) {
            $this->configurableBundleTemplateNameGenerator->generateConfigurableBundleTemplateName($configurableBundleTemplateTransfer);
            $this->configurableBundleTemplateTranslationWriter->saveTranslations($configurableBundleTemplateTransfer);
        }

        if (!$this->configurableBundleEntityManager->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer)) {
            $messageTransfer = (new MessageTransfer())
                ->setValue(static::ERROR_MESSAGE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND)
                ->setParameters([
                    static::ERROR_MESSAGE_PARAM_ID => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
                ]);

            return $this->createConfigurableBundleTemplateResponseTransfer($configurableBundleTemplateTransfer, $messageTransfer);
        }

        return $this->createConfigurableBundleTemplateResponseTransfer($configurableBundleTemplateTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\MessageTransfer|null $messageTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function createConfigurableBundleTemplateResponseTransfer(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ?MessageTransfer $messageTransfer = null
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateResponseTransfer = (new ConfigurableBundleTemplateResponseTransfer())
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setIsSuccessful($messageTransfer === null);

        if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            return $configurableBundleTemplateResponseTransfer;
        }

        return $configurableBundleTemplateResponseTransfer->addMessage($messageTransfer);
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idConfigurableBundleTemplate): void {
            $this->executeDeleteConfigurableBundleTemplateByIdTransaction($idConfigurableBundleTemplate);
        });
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    protected function executeDeleteConfigurableBundleTemplateByIdTransaction(int $idConfigurableBundleTemplate): void
    {
        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateSlotsByIdConfigurableBundleTemplate($idConfigurableBundleTemplate);
        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }
}
