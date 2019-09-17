<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReader;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface;
use Spryker\Zed\ConfigurableBundle\ConfigurableBundleDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 */
class ConfigurableBundleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface
     */
    public function createConfigurableBundleTemplateSlotReader(): ConfigurableBundleTemplateSlotReaderInterface
    {
        return new ConfigurableBundleTemplateSlotReader(
            $this->getRepository(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    protected function getGlossaryFacade(): ConfigurableBundleToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_GLOSSARY);
    }
}
