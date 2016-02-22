<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Shared\Transfer\TransferInterface;

interface ZedRequestClientInterface
{

    /**
     * @param string $url
     * @param \Spryker\Shared\Transfer\TransferInterface $object
     * @param int|null $timeoutInSeconds
     * @param bool|false $isBackgroundRequest
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $timeoutInSeconds = null, $isBackgroundRequest = false);

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getLastResponseInfoMessages();

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getLastResponseErrorMessages();

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getLastResponseSuccessMessages();

}
