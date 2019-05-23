<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\JsonApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;

interface RestResponseInterface
{
    public const RESPONSE_ERRORS = 'errors';
    public const RESPONSE_DATA = 'data';
    public const RESPONSE_INCLUDED = 'included';
    public const RESPONSE_LINKS = 'links';

    /**
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $error
     *
     * @return $this
     */
    public function addError(RestErrorMessageTransfer $error);

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer[]
     */
    public function getErrors(): array;

    /**
     * @param string $name
     * @param string $uri
     *
     * @return $this
     */
    public function addLink(string $name, string $uri);

    /**
     * @return array
     */
    public function getLinks(): array;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return $this
     */
    public function addResource(RestResourceInterface $restResource);

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getResources(): array;

    /**
     * @return int
     */
    public function getTotals(): int;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus(int $status);

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addHeader(string $key, string $value);

    /**
     * @return array
     */
    public function getHeaders(): array;
}
