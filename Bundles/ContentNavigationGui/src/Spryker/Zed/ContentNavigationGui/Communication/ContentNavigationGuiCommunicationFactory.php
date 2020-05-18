<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication;

use Spryker\Zed\ContentNavigationGui\Communication\Form\Constraint\ContentNavigationConstraint;
use Spryker\Zed\ContentNavigationGui\Communication\Form\DataProvider\NavigationContentTermFormDataProvider;
use Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationContentGuiEditorConfigurationMapper;
use Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationContentGuiEditorConfigurationMapperInterface;
use Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationFormDataMapper;
use Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationFormDataMapperInterface;
use Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationTermDataMapper;
use Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationTermDataMapperInterface;
use Spryker\Zed\ContentNavigationGui\ContentNavigationGuiDependencyProvider;
use Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToContentNavigationFacadeInterface;
use Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToNavigationFacadeInterface;
use Spryker\Zed\ContentNavigationGui\Dependency\Service\ContentNavigationGuiToUtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ContentNavigationGui\ContentNavigationGuiConfig getConfig()
 */
class ContentNavigationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ContentNavigationGui\Communication\Form\Constraint\ContentNavigationConstraint
     */
    public function createContentNavigationConstraint(): ContentNavigationConstraint
    {
        return new ContentNavigationConstraint(
            $this->getContentNavigationFacade(),
            $this->getUtilEncodeService()
        );
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Communication\Form\DataProvider\NavigationContentTermFormDataProvider
     */
    public function createNavigationContentTermFormDataProvider(): NavigationContentTermFormDataProvider
    {
        return new NavigationContentTermFormDataProvider($this->getNavigationFacade());
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationContentGuiEditorConfigurationMapperInterface
     */
    public function createContentNavigationContentGuiEditorConfigurationMapper(): ContentNavigationContentGuiEditorConfigurationMapperInterface
    {
        return new ContentNavigationContentGuiEditorConfigurationMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationFormDataMapperInterface
     */
    public function createContentNavigationFormDataMapper(): ContentNavigationFormDataMapperInterface
    {
        return new ContentNavigationFormDataMapper();
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Communication\Mapper\ContentNavigationTermDataMapperInterface
     */
    public function createContentNavigationTermDataMapper(): ContentNavigationTermDataMapperInterface
    {
        return new ContentNavigationTermDataMapper();
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToContentNavigationFacadeInterface
     */
    public function getContentNavigationFacade(): ContentNavigationGuiToContentNavigationFacadeInterface
    {
        return $this->getProvidedDependency(ContentNavigationGuiDependencyProvider::FACADE_CONTENT_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToNavigationFacadeInterface
     */
    public function getNavigationFacade(): ContentNavigationGuiToNavigationFacadeInterface
    {
        return $this->getProvidedDependency(ContentNavigationGuiDependencyProvider::FACADE_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Dependency\Service\ContentNavigationGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodeService(): ContentNavigationGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ContentNavigationGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
