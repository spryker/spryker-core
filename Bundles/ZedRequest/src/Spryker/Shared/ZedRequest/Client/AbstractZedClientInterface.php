<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

/**
 * @method \Spryker\Shared\Kernel\Transfer\TransferInterface call(string $url, \Spryker\Shared\Kernel\Transfer\TransferInterface $object, array $requestOptions = null)
 */
interface AbstractZedClientInterface
{
    /**
     * Specification:
     * - Adds metadata to a request.
     *
     * @api
     *
     * @param string $name
     * @param mixed $metaTransfer
     *
     * @return $this
     */
    public function addMetaTransfer($name, $metaTransfer);

    /**
     * Specification:
     * - Checks whether last response is available.
     *
     * @api
     *
     * @return bool
     */
    public function hasLastResponse();

    /**
     * Specification:
     * - Gets last response if available.
     * - If last response is not available, throws `BadMethodCallException` exception.
     *
     * @api
     *
     * @throws \BadMethodCallException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function getLastResponse();

    /**
     * Specification:
     * - Gets info status messages.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getInfoStatusMessages(): array;

    /**
     * Specification:
     * - Gets error status messages.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getErrorStatusMessages(): array;

    /**
     * Specification:
     * - Gets success status messages.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getSuccessStatusMessages(): array;
}
