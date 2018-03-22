<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Api\Adapter;

interface AdapterInterface
{
    /**
     * @param array|string $data
     *
     * @return string
     */
    public function sendRequest($data);

    /**
     * @param array|string $data
     * @param string $user
     * @param string $password
     *
     * @return string
     */
    public function sendAuthorizedRequest($data, $user, $password);
}
