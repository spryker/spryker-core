<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateWriter implements ConfigurableBundleTemplateWriterInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = 'Configurable bundle template with id "%id%" was not found.';
    protected const ERROR_MESSAGE_PARAM_ID = '%id%';
    protected const ERROR_MESSAGE_TYPE = 'error';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface
     */
    protected $configurableBundleTemplateTranslationWriter;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface
     */
    protected $configurableBundleNameGenerator;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface $eventFacade
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface $configurableBundleTemplateTranslationWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface $eventFacade
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTemplateTranslationWriterInterface $configurableBundleTemplateTranslationWriter,
        ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator,
        ConfigurableBundleToEventFacadeInterface $eventFacade
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleTemplateTranslationWriter = $configurableBundleTemplateTranslationWriter;
        $this->configurableBundleNameGenerator = $configurableBundleNameGenerator;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateTransfer) {
            return $this->executeCreateConfigurableBundleTemplateTransaction($configurableBundleTemplateTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateTransfer) {
            return $this->executeUpdateConfigurableBundleTemplateTransaction($configurableBundleTemplateTransfer);
        });
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function activateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->configurableBundleEntityManager->activateConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deactivateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->configurableBundleEntityManager->deactivateConfigurableBundleTemplateById($idConfigurableBundleTemplate);
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
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    protected function executeCreateConfigurableBundleTemplateTransaction(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleResponseTransfer {
        $configurableBundleTemplateTransfer = $this->configurableBundleNameGenerator->setConfigurableBundleTemplateName($configurableBundleTemplateTransfer);

        $configurableBundleTemplateTransfer = $this->configurableBundleEntityManager->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);
        $this->configurableBundleTemplateTranslationWriter->saveTranslations($configurableBundleTemplateTransfer);

        $this->triggerPublishEvent($configurableBundleTemplateTransfer);

        return $this->createConfigurableBundleResponseTransfer($configurableBundleTemplateTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    protected function executeUpdateConfigurableBundleTemplateTransaction(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleResponseTransfer {
        $configurableBundleTemplateTransfer = $this->configurableBundleNameGenerator->setConfigurableBundleTemplateName($configurableBundleTemplateTransfer);

        if (!$this->configurableBundleEntityManager->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer)) {
            $messageTransfer = (new MessageTransfer())
                ->setValue(static::ERROR_MESSAGE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND)
                ->setType(static::ERROR_MESSAGE_TYPE)
                ->setParameters([
                    static::ERROR_MESSAGE_PARAM_ID => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
                ]);

            return $this->createConfigurableBundleResponseTransfer($configurableBundleTemplateTransfer, $messageTransfer);
        }

        $this->configurableBundleTemplateTranslationWriter->saveTranslations($configurableBundleTemplateTransfer);

        $this->triggerPublishEvent($configurableBundleTemplateTransfer);

        return $this->createConfigurableBundleResponseTransfer($configurableBundleTemplateTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\MessageTransfer|null $messageTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    protected function createConfigurableBundleResponseTransfer(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ?MessageTransfer $messageTransfer = null
    ): ConfigurableBundleResponseTransfer {
        $configurableBundleResponseTransfer = (new ConfigurableBundleResponseTransfer())
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setIsSuccessful($messageTransfer === null);

        if ($configurableBundleResponseTransfer->getIsSuccessful()) {
            return $configurableBundleResponseTransfer;
        }

        return $configurableBundleResponseTransfer->addMessage($messageTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return void
     */
    protected function triggerPublishEvent(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): void
    {
        $this->eventFacade->trigger(
            ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_PUBLISH,
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
        );
    }
}
