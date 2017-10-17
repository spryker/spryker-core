<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilNetwork;

interface UtilNetworkServiceInterface
{
    /**
     * Specification:
     *  - Get current running script hostname
     *
     * @api
     *
     * @return string
     */
    public function getHostName();

    /**
     * Specification:
     *  - Get string to follow requests between applications
     *
     * @return string
     */
    public function getRequestId();
}
