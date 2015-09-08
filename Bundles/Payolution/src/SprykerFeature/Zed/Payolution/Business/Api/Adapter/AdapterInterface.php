<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter;

use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;

interface AdapterInterface
{

    public function sendRequest(AbstractRequest $request);

}
