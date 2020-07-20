<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\RestApiResource;

class CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceBridge implements CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiResourceInterface
     */
    protected $contentProductAbstractListsRestApiResource;

    /**
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiResourceInterface $contentProductAbstractListsRestApiResource
     */
    public function __construct($contentProductAbstractListsRestApiResource)
    {
        $this->contentProductAbstractListsRestApiResource = $contentProductAbstractListsRestApiResource;
    }

    /**
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param string[] $contentProductAbstractListKeys
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getContentProductAbstractListsByKeys(
        array $contentProductAbstractListKeys,
        string $localeName
    ): array {
        return $this->contentProductAbstractListsRestApiResource->getContentProductAbstractListsByKeys(
            $contentProductAbstractListKeys,
            $localeName
        );
    }
}
