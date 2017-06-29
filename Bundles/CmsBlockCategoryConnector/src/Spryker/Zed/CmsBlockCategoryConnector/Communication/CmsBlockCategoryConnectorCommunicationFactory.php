<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication;

use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorDependencyProvider;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider\CmsBlockCategoryDataProvider;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CmsBlockCategoryType;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel\CmsBlockCategoryConnectorCollector;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/** @method \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface getQueryContainer() * @method CmsBlockCategoryConnectorConfig getConfig()
 */
class CmsBlockCategoryConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @var string
     */
    protected $currentLocale;

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider\CmsBlockCategoryDataProvider
     */
    public function createCmsBlockCategoryDataProvider()
    {
        return new CmsBlockCategoryDataProvider(
            $this->getQueryContainer(),
            $this->getCategoryQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CmsBlockCategoryType
     */
    public function createCmsBlockCategoryType()
    {
        return new CmsBlockCategoryType();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        if ($this->currentLocale === null) {
            $this->currentLocale = $this->getLocaleFacade()
                ->getCurrentLocale();
        }

        return $this->currentLocale;
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsBlockCategoryConnectorDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockCategoryConnectorDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel\CmsBlockCategoryConnectorCollector
     */
    public function createCmsBlockCategoryStorageQueryContainer()
    {
        return new CmsBlockCategoryConnectorCollector();
    }

}
