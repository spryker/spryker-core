<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * Interface HttpClientInterface
 */
interface HttpClientInterface
{
    /**
     * @deprecated Please use ZedRequestConstants::CLIENT_OPTIONS to change the default timeout.
     *
     * @param int $timeoutInSeconds
     *
     * @return void
     */
    public static function setDefaultTimeout($timeoutInSeconds);

    /**
     * Do not use int for timeout settings anymore. If you want to change request settings
     * please make use of an array as described @see http://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @param string $pathInfo
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transferObject
     * @param array $metaTransfers
     * @param array|int|null $requestOptions
     *
     * @throws \LogicException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function request(
        $pathInfo,
        TransferInterface $transferObject = null,
        array $metaTransfers = [],
        $requestOptions = null
    );

    /**
     * Used for debug output
     *
     * @return int
     */
    public static function getRequestCounter();
}
