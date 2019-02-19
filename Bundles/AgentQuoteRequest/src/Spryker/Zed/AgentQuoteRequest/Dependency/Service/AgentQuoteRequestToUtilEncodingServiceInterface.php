<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Dependency\Service;

interface AgentQuoteRequestToUtilEncodingServiceInterface
{
    /**
     * @param mixed $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string
     */
    public function encodeJson($value, $options = null, $depth = null);
}
