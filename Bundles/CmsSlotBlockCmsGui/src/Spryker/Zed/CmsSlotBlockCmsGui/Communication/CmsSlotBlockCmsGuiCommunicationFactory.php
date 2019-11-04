<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication;

use Spryker\Zed\CmsSlotBlockCmsGui\CmsSlotBlockCmsGuiDependencyProvider;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageConditionForm;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\Constraint\CmsPageConditionsConstraint;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\DataProvider\CmsPageConditionDataProviderInterface;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerBridge;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CmsSlotBlockCmsGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageConditionForm
     */
    public function createCmsPageSlotBlockConditionForm(): CmsPageConditionForm
    {
        return new CmsPageConditionForm();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\DataProvider\CmsPageConditionDataProviderInterface
     */
    public function createCmsPageSlotBlockFormDataProvider(): CmsPageConditionDataProviderInterface
    {
        return new CmsPageConditionsDataProvider($this->getCmsQueryContainer());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\Constraint\CmsPageConditionsConstraint
     */
    public function createCmsPageConditionsConstraint(): CmsPageConditionsConstraint
    {
        return new CmsPageConditionsConstraint();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer(): CmsSlotBlockCmsGuiToCmsQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockCmsGuiDependencyProvider::QUERY_CONTAINER_CMS);
    }
}
