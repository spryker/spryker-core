<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use Generated\Shared\Transfer\StatusMessagesTransfer;

/**
 * @method \Spryker\Shared\Kernel\Transfer\TransferInterface call(string $url, \Spryker\Shared\Kernel\Transfer\TransferInterface $object, array $requestOptions = null)
 */
interface AbstractZedClientInterface
{
    /**
     * @param string $name
     * @param mixed $metaTransfer
     *
     * @return $this
     */
    public function addMetaTransfer($name, $metaTransfer);

    /**
     * @return bool
     */
    public function hasLastResponse();

    /**
     * @throws \BadMethodCallException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function getLastResponse();

    /**
     * @return Generated\Shared\Transfer\StatusMessagesTransfer;
     */
    public function getStatusMessages(): StatusMessagesTransfer;
}
