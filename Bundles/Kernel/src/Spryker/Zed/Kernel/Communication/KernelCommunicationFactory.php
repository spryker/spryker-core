<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\KernelDependencyProvider;

class KernelCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Kernel\Dependency\Facade\KernelToMessengerInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(KernelDependencyProvider::FACADE_MESSENGER);
    }

}
