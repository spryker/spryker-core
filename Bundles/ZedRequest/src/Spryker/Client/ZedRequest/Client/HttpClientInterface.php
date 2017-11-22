<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Client;

interface HttpClientInterface
{
    /**
     * Specification:
     * - returns an array of headers which should be used for the request.
     *
     * @api
     *
     * @return array
     */
    public function getHeaders();
}
