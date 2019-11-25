<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication;

use Spryker\Zed\CmsSlotBlockCmsGui\CmsSlotBlockCmsGuiDependencyProvider;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageSlotBlockConditionForm;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\Constraint\CmsPageConditionConstraint;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\DataProvider\CmsPageConditionDataProvider;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\DataProvider\CmsPageConditionDataProviderInterface;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToLocaleFacadeInterface;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToTranslatorFacadeInterface;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Service\CmsSlotBlockCmsGuiToUtilEncodingInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CmsSlotBlockCmsGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageSlotBlockConditionForm
     */
    public function createCmsPageConditionForm(): CmsPageSlotBlockConditionForm
    {
        return new CmsPageSlotBlockConditionForm();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\DataProvider\CmsPageConditionDataProviderInterface
     */
    public function createCmsPageConditionFormDataProvider(): CmsPageConditionDataProviderInterface
    {
        return new CmsPageConditionDataProvider(
            $this->getCmsQueryContainer(),
            $this->getTranslatorFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\Constraint\CmsPageConditionConstraint
     */
    public function createCmsPageConditionsConstraint(): CmsPageConditionConstraint
    {
        return new CmsPageConditionConstraint();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer(): CmsSlotBlockCmsGuiToCmsQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockCmsGuiDependencyProvider::QUERY_CONTAINER_CMS);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): CmsSlotBlockCmsGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockCmsGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CmsSlotBlockCmsGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockCmsGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Service\CmsSlotBlockCmsGuiToUtilEncodingInterface
     */
    public function getUtilEncoding(): CmsSlotBlockCmsGuiToUtilEncodingInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockCmsGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
