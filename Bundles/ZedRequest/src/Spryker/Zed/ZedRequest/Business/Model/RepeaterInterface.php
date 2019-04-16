<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Business\Model;

use Spryker\Shared\ZedRequest\Client\RequestInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

interface RepeaterInterface
{
    /**
     * @param string|null $moduleControllerAction
     *
     * @return array
     */
    public function getRepeatData($moduleControllerAction = null);

    /**
     * @param \Spryker\Shared\ZedRequest\Client\RequestInterface $transferObject
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return void
     */
    public function setRepeatData(RequestInterface $transferObject, HttpRequest $httpRequest);
}
