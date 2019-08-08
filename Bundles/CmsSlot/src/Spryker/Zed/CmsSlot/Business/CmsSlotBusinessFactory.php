<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business;

use Spryker\Zed\CmsSlot\Business\Activator\CmsSlotActivator;
use Spryker\Zed\CmsSlot\Business\Activator\CmsSlotActivatorInterface;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotConstraintsProvider;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotTemplateConstraintsProvider;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Business\Validator\CmsSlotTemplateValidator;
use Spryker\Zed\CmsSlot\Business\Validator\CmsSlotTemplateValidatorInterface;
use Spryker\Zed\CmsSlot\Business\Validator\CmsSlotValidator;
use Spryker\Zed\CmsSlot\Business\Validator\CmsSlotValidatorInterface;
use Spryker\Zed\CmsSlot\CmsSlotDependencyProvider;
use Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToSymfonyValidatorAdapterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsSlot\CmsSlotConfig getConfig()
 * @method \Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsSlot\Persistence\CmsSlotEntityManagerInterface getEntityManager()
 */
class CmsSlotBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsSlot\Business\Validator\CmsSlotValidatorInterface
     */
    public function createCmsSlotValidator(): CmsSlotValidatorInterface
    {
        return new CmsSlotValidator(
            $this->getValidatorAdapter(),
            $this->createCmsSlotConstraintsProvider()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlot\Business\Validator\CmsSlotTemplateValidatorInterface
     */
    public function createCmsSlotTemplateValidator(): CmsSlotTemplateValidatorInterface
    {
        return new CmsSlotTemplateValidator(
            $this->getValidatorAdapter(),
            $this->createCmsSlotTemplateConstraintsProvider()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface
     */
    public function createCmsSlotConstraintsProvider(): ConstraintsProviderInterface
    {
        return new CmsSlotConstraintsProvider();
    }

    /**
     * @return \Spryker\Zed\CmsSlot\Business\Activator\CmsSlotActivatorInterface
     */
    public function createCmsSlotActivator(): CmsSlotActivatorInterface
    {
        return new CmsSlotActivator(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface
     */
    public function createCmsSlotTemplateConstraintsProvider(): ConstraintsProviderInterface
    {
        return new CmsSlotTemplateConstraintsProvider();
    }

    /**
     * @return \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToSymfonyValidatorAdapterInterface
     */
    public function getValidatorAdapter(): CmsSlotToSymfonyValidatorAdapterInterface
    {
        return $this->getProvidedDependency(CmsSlotDependencyProvider::CMS_SLOT_VALIDATOR);
    }
}
