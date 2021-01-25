<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business;

use Spryker\Zed\CmsSlotBlock\Business\Reader\CmsSlotBlockReader;
use Spryker\Zed\CmsSlotBlock\Business\Reader\CmsSlotBlockReaderInterface;
use Spryker\Zed\CmsSlotBlock\Business\Reader\CmsSlotTemplateConditionReader;
use Spryker\Zed\CmsSlotBlock\Business\Reader\CmsSlotTemplateConditionReaderInterface;
use Spryker\Zed\CmsSlotBlock\Business\Validator\CmsSlotBlockValidator;
use Spryker\Zed\CmsSlotBlock\Business\Validator\CmsSlotBlockValidatorInterface;
use Spryker\Zed\CmsSlotBlock\Business\Writer\CmsSlotBlockRelationsWriter;
use Spryker\Zed\CmsSlotBlock\Business\Writer\CmsSlotBlockRelationsWriterInterface;
use Spryker\Zed\CmsSlotBlock\CmsSlotBlockDependencyProvider;
use Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToCmsSlotFacadeInterface;
use Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToEventFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsSlotBlock\CmsSlotBlockConfig getConfig()
 */
class CmsSlotBlockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotBlock\Business\Writer\CmsSlotBlockRelationsWriterInterface
     */
    public function createCmsSlotBlockRelationsWriter(): CmsSlotBlockRelationsWriterInterface
    {
        return new CmsSlotBlockRelationsWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getEventFacade(),
            $this->createCmsSlotBlockValidator()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Business\Reader\CmsSlotBlockReaderInterface
     */
    public function createCmsSlotBlockReader(): CmsSlotBlockReaderInterface
    {
        return new CmsSlotBlockReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Business\Reader\CmsSlotTemplateConditionReaderInterface
     */
    public function createCmsSlotTemplateConditionReader(): CmsSlotTemplateConditionReaderInterface
    {
        return new CmsSlotTemplateConditionReader($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Business\Validator\CmsSlotBlockValidatorInterface
     */
    public function createCmsSlotBlockValidator(): CmsSlotBlockValidatorInterface
    {
        return new CmsSlotBlockValidator($this->getCmsSlotFacade());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToEventFacadeInterface
     */
    public function getEventFacade(): CmsSlotBlockToEventFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToCmsSlotFacadeInterface
     */
    public function getCmsSlotFacade(): CmsSlotBlockToCmsSlotFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockDependencyProvider::FACADE_CMS_SLOT);
    }
}
