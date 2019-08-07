<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication;

use Generated\Shared\Transfer\ContentTransfer;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\ContentGui\Communication\Form\ContentForm;
use Spryker\Zed\ContentGui\Communication\Form\DataProvider\ContentFormDataProvider;
use Spryker\Zed\ContentGui\Communication\Form\DataProvider\ContentFormDataProviderInterface;
use Spryker\Zed\ContentGui\Communication\Resolver\ContentEditorPluginsResolver;
use Spryker\Zed\ContentGui\Communication\Resolver\ContentEditorPluginsResolverInterface;
use Spryker\Zed\ContentGui\Communication\Resolver\ContentResolver;
use Spryker\Zed\ContentGui\Communication\Resolver\ContentResolverInterface;
use Spryker\Zed\ContentGui\Communication\Table\ContentByTypeTable;
use Spryker\Zed\ContentGui\Communication\Table\ContentTable;
use Spryker\Zed\ContentGui\Communication\Tabs\ContentTabs;
use Spryker\Zed\ContentGui\ContentGuiDependencyProvider;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToLocaleFacadeInterface;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilEncodingInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 * @method \Spryker\Zed\ContentGui\ContentGuiConfig getConfig()
 */
class ContentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ContentGui\Communication\Table\ContentTable
     */
    public function createContentTable(): ContentTable
    {
        return new ContentTable(
            $this->getPropelContentQuery(),
            $this->getContentPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ContentGui\Communication\Tabs\ContentTabs
     */
    public function createContentTabs(): ContentTabs
    {
        return new ContentTabs($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ContentGui\Communication\Form\DataProvider\ContentFormDataProviderInterface
     */
    public function createContentFormDataProvider(): ContentFormDataProviderInterface
    {
        return new ContentFormDataProvider(
            $this->createContentResolver(),
            $this->getContentFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ContentGui\Communication\Resolver\ContentResolverInterface
     */
    public function createContentResolver(): ContentResolverInterface
    {
        return new ContentResolver($this->getContentPlugins());
    }

    /**
     * @param string $contentType
     * @param string|null $contentKey
     *
     * @return \Spryker\Zed\ContentGui\Communication\Table\ContentByTypeTable
     */
    public function createContentByTypeTable(string $contentType, ?string $contentKey = null): ContentByTypeTable
    {
        return new ContentByTypeTable(
            $contentType,
            $this->getPropelContentQuery(),
            $contentKey
        );
    }

    /**
     * @return \Spryker\Zed\ContentGui\Communication\Resolver\ContentEditorPluginsResolverInterface
     */
    public function createContentEditorPluginsResolver(): ContentEditorPluginsResolverInterface
    {
        return new ContentEditorPluginsResolver($this->getContentEditorPlugins());
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getContentForm(?ContentTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ContentForm::class, $data, $options);
    }

    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    public function getPropelContentQuery(): SpyContentQuery
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::PROPEL_QUERY_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ContentGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface
     */
    public function getContentFacade(): ContentGuiToContentFacadeInterface
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::FACADE_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface[]
     */
    public function getContentPlugins(): array
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::PLUGINS_CONTENT_ITEM);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilEncodingInterface
     */
    public function getUtilEncoding(): ContentGuiToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[]
     */
    public function getContentEditorPlugins(): array
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::PLUGINS_CONTENT_EDITOR);
    }
}
