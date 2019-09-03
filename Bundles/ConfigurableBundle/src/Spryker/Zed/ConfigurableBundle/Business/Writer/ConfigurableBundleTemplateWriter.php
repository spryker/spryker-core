<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateWriter implements ConfigurableBundleTemplateWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface
     */
    protected $configurableBundleTemplateNameGenerator;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface $configurableBundleTemplateNameGenerator
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade,
        ConfigurableBundleTemplateNameGeneratorInterface $configurableBundleTemplateNameGenerator
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->glossaryFacade = $glossaryFacade;
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

        $configurableBundleTemplateTransfer = $this->configurableBundleTemplateNameGenerator
            ->generateConfigurableBundleTemplateName($configurableBundleTemplateTransfer);

        $this->configurableBundleEntityManager->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);
        $configurableBundleTemplateTransfer = $this->configurableBundleTemplateNameGenerator
            ->generateConfigurableBundleTemplateTranslationKey($configurableBundleTemplateTransfer);
        $this->createConfigurableBundleTemplateTranslations($configurableBundleTemplateTransfer);

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
        if (!$this->configurableBundleEntityManager->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer)) {
            return (new ConfigurableBundleTemplateResponseTransfer())
                ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
                ->setIsSuccessful(false);
        }

        if (!$configurableBundleTemplateTransfer->getTranslationKey()) {
            $configurableBundleTemplateTransfer = $this->configurableBundleTemplateNameGenerator
                ->generateConfigurableBundleTemplateTranslationKey($configurableBundleTemplateTransfer);
        }

        $this->updateConfigurableBundleTemplateTranslations($configurableBundleTemplateTransfer);

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return void
     */
    protected function createConfigurableBundleTemplateTranslations(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): void {
        $this->glossaryFacade->createKey($configurableBundleTemplateTransfer->getTranslationKey());

        foreach ($configurableBundleTemplateTransfer->getTranslations() as $configurableBundleTemplateTranslationTransfer) {
            $this->glossaryFacade->createTranslation(
                $configurableBundleTemplateTransfer->getTranslationKey(),
                $configurableBundleTemplateTranslationTransfer->getLocale(),
                $configurableBundleTemplateTranslationTransfer->getName()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return void
     */
    protected function updateConfigurableBundleTemplateTranslations(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): void {
        foreach ($configurableBundleTemplateTransfer->getTranslations() as $configurableBundleTemplateTranslationTransfer) {
            $this->glossaryFacade->updateTranslation(
                $configurableBundleTemplateTransfer->getTranslationKey(),
                $configurableBundleTemplateTranslationTransfer->getLocale(),
                $configurableBundleTemplateTranslationTransfer->getName()
            );
        }
    }
}
