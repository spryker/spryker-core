<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ResponseRelationshipInterface
{
    /**
     * @param string $resourceName
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string|null $parentResourceId
     *
     * @return void
     */
    public function loadRelationships(
        string $resourceName,
        array $resources,
        RestRequestInterface $restRequest,
        ?string $parentResourceId = null
    ): void;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    public function processIncluded(array $resources, RestRequestInterface $restRequest): array;

    /**
     * @param string $resourceType
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    public function hasRelationship(string $resourceType, RestRequestInterface $restRequest): bool;
}
