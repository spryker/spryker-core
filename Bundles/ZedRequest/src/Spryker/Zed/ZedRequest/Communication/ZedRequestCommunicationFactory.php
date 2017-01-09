<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ZedRequest\ZedRequestDependencyProvider;

class ZedRequestCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\ZedRequest\Dependency\Facade\ZedRequestToMessengerInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::FACADE_MESSENGER);
    }

}
