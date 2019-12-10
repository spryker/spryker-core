<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Updater;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundle\Business\EventTriggerer\EventTriggererInterface;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateUpdater implements ConfigurableBundleTemplateUpdaterInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_ACTIVATED = 'configurable_bundle.template.validation.error.already_activated';
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_DEACTIVATED = 'configurable_bundle.template.validation.error.already_deactivated';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface
     */
    protected $configurableBundleTranslationWriter;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface
     */
    protected $configurableBundleNameGenerator;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface
     */
    protected $configurableBundleTemplateReader;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\EventTriggerer\EventTriggererInterface
     */
    protected $eventTriggerer;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface $configurableBundleTranslationWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator
     * @param \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader
     * @param \Spryker\Zed\ConfigurableBundle\Business\EventTriggerer\EventTriggererInterface $eventTriggerer
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTranslationWriterInterface $configurableBundleTranslationWriter,
        ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator,
        ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader,
        EventTriggererInterface $eventTriggerer
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleTranslationWriter = $configurableBundleTranslationWriter;
        $this->configurableBundleNameGenerator = $configurableBundleNameGenerator;
        $this->configurableBundleTemplateReader = $configurableBundleTemplateReader;
        $this->eventTriggerer = $eventTriggerer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateResponseTransfer = $this->getConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        if (!$configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            return $configurableBundleTemplateResponseTransfer;
        }

        $configurableBundleTemplateTransfer
            ->setName($this->configurableBundleNameGenerator->generateTemplateName($configurableBundleTemplateTransfer));

        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateTransfer) {
            return $this->executeUpdateConfigurableBundleTemplateTransaction($configurableBundleTemplateTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function activateConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateResponseTransfer = $this->configurableBundleTemplateReader
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if (!$configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            return $configurableBundleTemplateResponseTransfer;
        }

        $configurableBundleTemplateTransfer = $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate();

        if ($configurableBundleTemplateTransfer->getIsActive() === true) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_ACTIVATED);
        }

        $configurableBundleTemplateTransfer->setIsActive(true);
        $configurableBundleTemplateTransfer = $this->configurableBundleEntityManager->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        $this->eventTriggerer->triggerConfigurableBundleTemplatePublishEvent($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function deactivateConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateResponseTransfer = $this->configurableBundleTemplateReader
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if (!$configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            return $configurableBundleTemplateResponseTransfer;
        }

        $configurableBundleTemplateTransfer = $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate();

        if ($configurableBundleTemplateTransfer->getIsActive() === false) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_DEACTIVATED);
        }

        $configurableBundleTemplateTransfer->setIsActive(false);
        $configurableBundleTemplateTransfer = $this->configurableBundleEntityManager->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        $this->eventTriggerer->triggerConfigurableBundleTemplatePublishEvent($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function executeUpdateConfigurableBundleTemplateTransaction(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateTransfer = $this->configurableBundleEntityManager
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        $this->configurableBundleTranslationWriter->saveTemplateTranslations($configurableBundleTemplateTransfer);

        $this->eventTriggerer->triggerConfigurableBundleTemplatePublishEvent($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function getConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateTransfer->requireIdConfigurableBundleTemplate();

        $configurableBundleTemplateResponseTransfer = $this->configurableBundleTemplateReader
            ->getConfigurableBundleTemplateById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        return $configurableBundleTemplateResponseTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function getErrorResponse(string $message): ConfigurableBundleTemplateResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
