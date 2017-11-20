<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ZedRequestClientInterface
{
    /**
     * Specification:
     * - Prepare and make the call to Zed.
     *
     * Third argument has changed from int to array. BC compatibility method will
     * convert the previous accepted integer to `['timeout => $timeoutInSeconds]`
     *
     * @api
     *
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array|int|null $requestOptions
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $requestOptions = null);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseInfoMessages();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseSuccessMessages();
}
