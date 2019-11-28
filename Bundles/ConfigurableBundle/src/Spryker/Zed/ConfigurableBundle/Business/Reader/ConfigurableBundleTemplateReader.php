<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateImageSetExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateReader implements ConfigurableBundleTemplateReaderInterface
{
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS = 'configurable_bundle.template.validation.error.not_exists';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface
     */
    protected $configurableBundleTranslationExpander;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateImageSetExpanderInterface
     */
    protected $configurableBundleTemplateImageSetExpander;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateImageSetExpanderInterface $configurableBundleTemplateImageSetExpander
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander,
        ConfigurableBundleToLocaleFacadeInterface $localeFacade,
        ConfigurableBundleTemplateImageSetExpanderInterface $configurableBundleTemplateImageSetExpander
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->configurableBundleTranslationExpander = $configurableBundleTranslationExpander;
        $this->localeFacade = $localeFacade;
        $this->configurableBundleTemplateImageSetExpander = $configurableBundleTemplateImageSetExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function getConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateTransfer = $this->configurableBundleRepository
            ->findConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if (!$configurableBundleTemplateTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS);
        }

        $configurableBundleTemplateTransfer = $this->expandConfigurableBundleTemplate(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplateFilterTransfer
        );

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function getConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): ConfigurableBundleTemplateResponseTransfer
    {
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->setTranslationLocales(new ArrayObject([$this->getDefaultLocale()]));

        return $this->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function getConfigurableBundleTemplateCollection(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateCollectionTransfer {
        $configurableBundleTemplateCollectionTransfer = new ConfigurableBundleTemplateCollectionTransfer();

        $configurableBundleTemplateTransfers = $this->configurableBundleRepository
            ->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer)
            ->getConfigurableBundleTemplates();

        foreach ($configurableBundleTemplateTransfers as $configurableBundleTemplateTransfer) {
            $expandedConfigurableBundleTemplateTransfer = $this->expandConfigurableBundleTemplate(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplateFilterTransfer
            );

            $configurableBundleTemplateCollectionTransfer->addConfigurableBundleTemplate($expandedConfigurableBundleTemplateTransfer);
        }

        return $configurableBundleTemplateCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function expandConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateTransfer = $this->configurableBundleTranslationExpander
            ->expandConfigurableBundleTemplateWithTranslations(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplateFilterTransfer->getTranslationLocales()
            );

        $configurableBundleTemplateTransfer = $this->configurableBundleTemplateImageSetExpander
            ->expandConfigurableBundleTemplateWithImageSets(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplateFilterTransfer->getTranslationLocales()
            );

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getDefaultLocale(): LocaleTransfer
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        return array_shift($localeTransfers);
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
