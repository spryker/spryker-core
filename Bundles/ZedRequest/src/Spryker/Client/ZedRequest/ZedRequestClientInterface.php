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
     * @param array|int|null $requestOptions Deprecated: Do not use "int" anymore, please use an array for requestOptions.
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $requestOptions = null);

    /**
     * Specification:
     * - Returns an array of MessageTransfers containing info messages for the last response.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseInfoMessages();

    /**
     * Specification:
     * - Returns an array of MessageTransfers containing error messages for the last response.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages();

    /**
     * Specification:
     * - Returns an array of MessageTransfers containing success messages for the last response.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseSuccessMessages();

    /**
     * Specification:
     *  - Get messages from Zed request and put them to session in next order:
     *  - Writes error message to flash bag.
     *  - Writes success message to flash bag.
     *  - Writes informational message to flash bag.
     *
     * @api
     *
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();
}
