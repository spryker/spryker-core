<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ContentProductAbstractListReaderInterface
{
    /**
     * @param array<string> $contentProductAbstractListKeys
     * @param string $localeName
     *
     * @return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function getContentProductAbstractListsResources(array $contentProductAbstractListKeys, string $localeName): array;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentProductAbstractListsById(RestRequestInterface $restRequest): RestResponseInterface;
}
