<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartNote\Business;

use Spryker\Zed\ConfigurableBundleCartNote\Business\Setter\ConfigurableBundleCartNoteSetter;
use Spryker\Zed\ConfigurableBundleCartNote\Business\Setter\ConfigurableBundleCartNoteSetterInterface;
use Spryker\Zed\ConfigurableBundleCartNote\ConfigurableBundleCartNoteDependencyProvider;
use Spryker\Zed\ConfigurableBundleCartNote\Dependency\Facade\ConfigurableBundleCartNoteToQuoteFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class ConfigurableBundleCartNoteBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundleCartNote\Business\Setter\ConfigurableBundleCartNoteSetterInterface
     */
    public function createConfigurableBundleCartNoteSetter(): ConfigurableBundleCartNoteSetterInterface
    {
        return new ConfigurableBundleCartNoteSetter($this->getQuoteFacade());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleCartNote\Dependency\Facade\ConfigurableBundleCartNoteToQuoteFacadeInterface
     */
    public function getQuoteFacade(): ConfigurableBundleCartNoteToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartNoteDependencyProvider::FACADE_QUOTE);
    }
}
