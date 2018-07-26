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
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addError(RestErrorMessageTransfer $error): self;

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer[]
     */
    public function getErrors(): array;

    /**
     * @param string $name
     * @param string $uri
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addLink(string $name, string $uri): self;

    /**
     * @return array
     */
    public function getLinks(): array;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addResource(RestResourceInterface $restResource): self;

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
     * @param string $key
     * @param string $value
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addHeader(string $key, string $value): self;

    /**
     * @return array
     */
    public function getHeaders(): array;
}
