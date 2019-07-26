<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business;

use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotConstraintsProvider;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotTemplateConstraintsProvider;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotTemplateConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Business\Validator\CmsSlotTemplateValidator;
use Spryker\Zed\CmsSlot\Business\Validator\CmsSlotTemplateValidatorInterface;
use Spryker\Zed\CmsSlot\Business\Validator\CmsSlotValidator;
use Spryker\Zed\CmsSlot\Business\Validator\CmsSlotValidatorInterface;
use Spryker\Zed\CmsSlot\CmsSlotDependencyProvider;
use Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsSlot\CmsSlotConfig getConfig()
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
     * @return \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotConstraintsProviderInterface
     */
    public function createCmsSlotConstraintsProvider(): CmsSlotConstraintsProviderInterface
    {
        return new CmsSlotConstraintsProvider();
    }

    /**
     * @return \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotTemplateConstraintsProviderInterface
     */
    public function createCmsSlotTemplateConstraintsProvider(): CmsSlotTemplateConstraintsProviderInterface
    {
        return new CmsSlotTemplateConstraintsProvider();
    }

    /**
     * @return \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface
     */
    public function getValidatorAdapter(): CmsSlotToValidationAdapterInterface
    {
        return $this->getProvidedDependency(CmsSlotDependencyProvider::ADAPTER_VALIDATION);
    }
}
