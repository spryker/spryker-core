<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Dependency\Client;

interface ContentBannersRestApiToContentStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves content by keys through a storage client dependency.
     * - Returns the context needed to generate a content type.
     *
     * @api
     *
     * @param string[] $contentKeys
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer[]
     */
    public function getContentTypeContextByKeys(array $contentKeys, string $localeName): array;
}
