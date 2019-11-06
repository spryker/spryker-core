<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    public function mapConfigurableBundleTemplateTransferToPageSearchTransfer(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        LocaleTransfer $localeTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        $configurableBundleTemplatePageSearchTransfer = $this->mapConfigurableBundleTemplateTransferToConfigurableBundleTemplatePageSearchTransfer(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplatePageSearchTransfer
        );

        $configurableBundleTemplatePageSearchTransfer->setLocale($localeTransfer->getLocaleName());

        $configurableBundleTemplatePageSearchTransfer = $this->executeConfigurableBundleTemplatePageDataExpanderPlugins(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplatePageSearchTransfer
        );

        $configurableBundleTemplatePageSearchTransfer->setData(
            $this->getConfigurableBundleTemplatePageSearchTransferData($configurableBundleTemplatePageSearchTransfer, $localeTransfer)
        );

        $configurableBundleTemplatePageSearchTransfer->setStructuredData(
            $this->getConfigurableBundleTemplatePageSearchTransferStructuredData($configurableBundleTemplatePageSearchTransfer)
        );

        return $configurableBundleTemplatePageSearchTransfer;
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
     * @return string|null
     */
    protected function getConfigurableBundleTemplatePageSearchTransferData(
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer,
        LocaleTransfer $localeTransfer
    ): ?string {
        $configurableBundleTemplatePageSearchTransferData = $configurableBundleTemplatePageSearchTransfer->toArray(true, true);

        $data = $this->searchFacade->transformPageMapToDocumentByMapperName(
            $configurableBundleTemplatePageSearchTransferData,
            $localeTransfer,
            ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME
        );

        return $this->utilEncodingService->encodeJson($data);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return string|null
     */
    protected function getConfigurableBundleTemplatePageSearchTransferStructuredData(ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer): ?string
    {
        $structuredData = $configurableBundleTemplatePageSearchTransfer->toArray(true, true);

        unset($structuredData[ConfigurableBundleTemplatePageSearchTransfer::ID_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH]);
        unset($structuredData[ConfigurableBundleTemplatePageSearchTransfer::STRUCTURED_DATA]);

        return $this->utilEncodingService->encodeJson($structuredData);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    protected function executeConfigurableBundleTemplatePageDataExpanderPlugins(
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
