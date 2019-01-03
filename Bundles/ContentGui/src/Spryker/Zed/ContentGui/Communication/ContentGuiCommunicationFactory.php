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
use Spryker\Zed\ContentGui\Communication\Table\ContentTable;
use Spryker\Zed\ContentGui\Communication\Tabs\ContentTabs;
use Spryker\Zed\ContentGui\ContentGuiDependencyProvider;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class ContentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ContentGui\Communication\Table\ContentTable
     */
    public function createContentTable(): ContentTable
    {
        return new ContentTable(
            $this->getPropelContentQuery(),
            $this->getUtilDateTimeService()
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
     * @return \Spryker\Zed\ContentGui\Communication\Form\DataProvider\ContentFormDataProvider
     */
    public function createContentFormDataProvider(): ContentFormDataProvider
    {
        return new ContentFormDataProvider($this->getContentFacade(), $this->getLocaleFacade());
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
    protected function getPropelContentQuery(): SpyContentQuery
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::PROPEL_QUERY_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeService(): ContentGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridgeInterface
     */
    public function getContentFacade()
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::FACADE_CONTENT);
    }
}
