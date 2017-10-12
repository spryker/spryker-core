<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Adapter;

interface AdapterInterface
{
    /**
     * @param string $data
     *
     * @return string
     */
    public function sendRequest($data);
}
