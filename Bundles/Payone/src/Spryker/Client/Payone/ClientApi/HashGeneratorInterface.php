<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payone\ClientApi;

use Spryker\Client\Payone\ClientApi\Request\AbstractRequest;

interface HashGeneratorInterface
{

    /**
     * @param \Spryker\Client\Payone\ClientApi\Request\AbstractRequest $request
     * @param string $securityKey
     *
     * @return string
     */
    public function generateHash(AbstractRequest $request, $securityKey);

}
