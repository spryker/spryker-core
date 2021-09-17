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
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseInfoMessages();

    /**
     * Specification:
     * - Returns an array of MessageTransfers containing error messages for the last response.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseErrorMessages();

    /**
     * Specification:
     * - Returns an array of MessageTransfers containing success messages for the last response.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseSuccessMessages();

    /**
     * Specification:
     *  - Get messages from Zed request and put them to session in next order:
     *  - Writes error message to flash bag.
     *  - Writes success message to flash bag.
     *  - Writes informational message to flash bag.
     * This behavior is different from addResponseMessagesToMessenger(). Method inspects only the last Zed request.
     *
     * @api
     *
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();

    /**
     * Specification:
     * - Returns an array of MessageTransfers containing info messages for all zed responses.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getResponsesInfoMessages(): array;

    /**
     * Specification:
     * - Returns an array of MessageTransfers containing error messages for all zed responses.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getResponsesErrorMessages(): array;

    /**
     * Specification:
     * - Returns an array of MessageTransfers containing success messages for all zed responses
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getResponsesSuccessMessages(): array;

    /**
     * Specification:
     *  - Get messages from all previous Zed requests in this Yves request cycle and put them to session in next order:
     *  - Writes error messages to flash bag.
     *  - Writes success messages to flash bag.
     *  - Writes informationals message to flash bag.
     * This behavior is different from addFlashMessagesFromLastZedRequest which only inspects the most recent Zed request.
     *
     * @api
     *
     * @return void
     */
    public function addResponseMessagesToMessenger(): void;

    /**
     * Specification:
     * - Returns an authorization token.
     *
     * @api
     *
     * @return string
     */
    public function getAuthToken(): string;

    /**
     * Specification:
     * - Returns a request id to follow the request flow.
     *
     * @api
     *
     * @return string
     */
    public function getRequestId(): string;
}
