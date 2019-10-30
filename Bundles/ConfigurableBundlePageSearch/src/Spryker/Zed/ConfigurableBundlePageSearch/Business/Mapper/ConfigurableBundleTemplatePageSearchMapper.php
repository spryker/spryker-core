<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToSearchFacadeInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Service\ConfigurableBundlePageSearchToUtilEncodingServiceInterface;

class ConfigurableBundleTemplatePageSearchMapper implements ConfigurableBundleTemplatePageSearchMapperInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Service\ConfigurableBundlePageSearchToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToSearchFacadeInterface
     */
    protected $searchFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplatePageDataExpanderPluginInterface[]
     */
    protected $configurableBundleTemplatePageDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Service\ConfigurableBundlePageSearchToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToSearchFacadeInterface $searchFacade
     * @param \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplatePageDataExpanderPluginInterface[] $configurableBundleTemplatePageDataExpanderPlugins
     */
    public function __construct(
        ConfigurableBundlePageSearchToUtilEncodingServiceInterface $utilEncodingService,
        ConfigurableBundlePageSearchToSearchFacadeInterface $searchFacade,
        array $configurableBundleTemplatePageDataExpanderPlugins
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->searchFacade = $searchFacade;
        $this->configurableBundleTemplatePageDataExpanderPlugins = $configurableBundleTemplatePageDataExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer $configurableBundleTemplateTranslationTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    public function mapDataToConfigurableBundleTemplatePageSearchTransfer(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplateTranslationTransfer $configurableBundleTemplateTranslationTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        $configurableBundleTemplatePageSearchTransfer = $this->mapConfigurableBundleTemplateTransferToConfigurableBundleTemplatePageSearchTransfer(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplatePageSearchTransfer
        );
        $configurableBundleTemplatePageSearchTransfer = $this->mapConfigurableBundleTemplateTranslationTransferToConfigurableBundleTemplatePageSearchTransfer(
            $configurableBundleTemplateTranslationTransfer,
            $configurableBundleTemplatePageSearchTransfer
        );

        $configurableBundleTemplatePageSearchTransfer = $this->expandConfigurableBundlePageSearchWithPlugins(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplatePageSearchTransfer
        );

        $configurableBundleTemplatePageSearchTransfer = $this->mapConfigurableBundleTemplatePageSearchTransferData(
            $configurableBundleTemplatePageSearchTransfer,
            $configurableBundleTemplateTranslationTransfer->getLocale()
        );

        return $this->mapConfigurableBundleTemplatePageSearchTransferStructuredData($configurableBundleTemplatePageSearchTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer $configurableBundleTemplateTranslationTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    protected function mapConfigurableBundleTemplateTranslationTransferToConfigurableBundleTemplatePageSearchTransfer(
        ConfigurableBundleTemplateTranslationTransfer $configurableBundleTemplateTranslationTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        $translations = $configurableBundleTemplateTranslationTransfer->toArray(true, true);
        unset($translations[ConfigurableBundleTemplateTranslationTransfer::LOCALE]);

        return $configurableBundleTemplatePageSearchTransfer->setLocale(
            $configurableBundleTemplateTranslationTransfer->getLocale()->getLocaleName()
        )->setTranslations($translations);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    protected function mapConfigurableBundleTemplateTransferToConfigurableBundleTemplatePageSearchTransfer(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        $configurableBundleTemplatePageSearchTransfer = $configurableBundleTemplatePageSearchTransfer->fromArray(
            $configurableBundleTemplateTransfer->toArray(),
            true
        );

        return $configurableBundleTemplatePageSearchTransfer->setType(ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME)
            ->setFkConfigurableBundleTemplate(
                $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    protected function mapConfigurableBundleTemplatePageSearchTransferData(
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer,
        LocaleTransfer $localeTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        $configurableBundleTemplatePageSearchTransferData = $configurableBundleTemplatePageSearchTransfer->toArray(true, true);

        $data = $this->searchFacade->transformPageMapToDocumentByMapperName(
            $configurableBundleTemplatePageSearchTransferData,
            $localeTransfer,
            ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME
        );

        return $configurableBundleTemplatePageSearchTransfer->setData(
            $this->utilEncodingService->encodeJson($data)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    protected function mapConfigurableBundleTemplatePageSearchTransferStructuredData(ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer): ConfigurableBundleTemplatePageSearchTransfer
    {
        $data = $configurableBundleTemplatePageSearchTransfer->toArray(true, true);

        unset($data[ConfigurableBundleTemplatePageSearchTransfer::STRUCTURED_DATA]);

        return $configurableBundleTemplatePageSearchTransfer->setStructuredData(
            $this->utilEncodingService->encodeJson($data)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    protected function expandConfigurableBundlePageSearchWithPlugins(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        foreach ($this->configurableBundleTemplatePageDataExpanderPlugins as $configurableBundleTemplatePageDataExpanderPlugin) {
            $configurableBundleTemplatePageSearchTransfer = $configurableBundleTemplatePageDataExpanderPlugin->expand(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplatePageSearchTransfer
            );
        }

        return $configurableBundleTemplatePageSearchTransfer;
    }
}
