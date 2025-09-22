<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface AgentToZedRequestClientInterface
{
    /**
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array<mixed, mixed>|null $requestOptions
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $requestOptions = null);
}
