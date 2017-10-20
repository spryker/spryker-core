<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNetwork;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilNetwork\UtilNetworkServiceFactory getFactory()
 */
class UtilNetworkService extends AbstractService implements UtilNetworkServiceInterface
{
    /**
     * Specification:
     *  - Get current running script hostname
     *
     * @api
     *
     * @return string
     */
    public function getHostName()
    {
        return $this->getFactory()
            ->createHost()
            ->getHostname();
    }

    /**
     * Specification:
     *  - Get string to follow requests between applications
     *
     * @return string
     */
    public function getRequestId()
    {
        return $this->getFactory()->createRequestId()->getRequestId();
    }
}
