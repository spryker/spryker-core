<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Creator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateCreator implements ConfigurableBundleTemplateCreatorInterface
{
    use TransactionTrait;

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
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface $configurableBundleTemplateTranslationWriter
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTemplateTranslationWriterInterface $configurableBundleTemplateTranslationWriter,
        ConfigurableBundleNameGeneratorInterface $configurableBundleNameGenerator
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleTemplateTranslationWriter = $configurableBundleTemplateTranslationWriter;
        $this->configurableBundleNameGenerator = $configurableBundleNameGenerator;
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
    protected function executeCreateConfigurableBundleTemplateTransaction(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateTransfer->setName(
            $this->configurableBundleNameGenerator->generateTemplateName($configurableBundleTemplateTransfer)
        );

        $configurableBundleTemplateTransfer = $this->configurableBundleEntityManager->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);
        $this->configurableBundleTemplateTranslationWriter->saveTranslations($configurableBundleTemplateTransfer);

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setIsSuccessful(true);
    }
}
