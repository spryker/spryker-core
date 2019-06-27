<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SetupFrontend\SetupFrontendDependencyProvider;

/**
 * @method \Spryker\Zed\SetupFrontend\Business\SetupFrontendFacadeInterface getFacade()
 * @method \Spryker\Zed\SetupFrontend\SetupFrontendConfig getConfig()
 */
class SetupFrontendCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return string
     */
    public function getStoreName(): string
    {
        return $this->getProvidedDependency(SetupFrontendDependencyProvider::STORE_NAME);
    }
}
