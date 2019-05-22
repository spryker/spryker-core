<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business;

use Spryker\Zed\ContentGui\Business\Converter\CmsBlockGui\CmsBlockGuiGlossaryConverter;
use Spryker\Zed\ContentGui\Business\Converter\CmsBlockGui\CmsBlockGuiGlossaryConverterInterface;
use Spryker\Zed\ContentGui\Business\Converter\CmsGui\CmsGuiGlossaryConverter;
use Spryker\Zed\ContentGui\Business\Converter\CmsGui\CmsGuiGlossaryConverterInterface;
use Spryker\Zed\ContentGui\Business\Converter\HtmlConverterInterface;
use Spryker\Zed\ContentGui\Business\Converter\HtmlToShortCodeConverter;
use Spryker\Zed\ContentGui\Business\Converter\ShortCodeConverterInterface;
use Spryker\Zed\ContentGui\Business\Converter\ShortCodeToHtmlConverter;
use Spryker\Zed\ContentGui\ContentGuiDependencyProvider;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ContentGui\ContentGuiConfig getConfig()
 */
class ContentGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ContentGui\Business\Converter\CmsGui\CmsGuiGlossaryConverterInterface
     */
    public function createCmsGuiGlossaryConverter(): CmsGuiGlossaryConverterInterface
    {
        return new CmsGuiGlossaryConverter(
            $this->createHtmlToShortCodeConverter(),
            $this->createShortCodeToHtmlConverter()
        );
    }

    /**
     * @return \Spryker\Zed\ContentGui\Business\Converter\CmsBlockGui\CmsBlockGuiGlossaryConverterInterface
     */
    public function createCmsBlockGuiGlossaryConverter(): CmsBlockGuiGlossaryConverterInterface
    {
        return new CmsBlockGuiGlossaryConverter(
            $this->createHtmlToShortCodeConverter(),
            $this->createShortCodeToHtmlConverter()
        );
    }

    /**
     * @return \Spryker\Zed\ContentGui\Business\Converter\HtmlConverterInterface
     */
    public function createHtmlToShortCodeConverter(): HtmlConverterInterface
    {
        return new HtmlToShortCodeConverter();
    }

    /**
     * @return \Spryker\Zed\ContentGui\Business\Converter\ShortCodeConverterInterface
     */
    public function createShortCodeToHtmlConverter(): ShortCodeConverterInterface
    {
        return new ShortCodeToHtmlConverter(
            $this->getContentFacade(),
            $this->getConfig(),
            $this->getTranslatorFacade(),
            $this->getContentEditorPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[]
     */
    public function getContentEditorPlugins(): array
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::PLUGINS_CONTENT_EDITOR);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface
     */
    public function getContentFacade(): ContentGuiToContentFacadeInterface
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::FACADE_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ContentGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::FACADE_TRANSLATOR);
    }
}
