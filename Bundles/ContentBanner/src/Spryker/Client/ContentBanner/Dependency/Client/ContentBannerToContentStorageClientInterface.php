<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Dependency\Client;

use Generated\Shared\Transfer\ContentTypeContextTransfer;

interface ContentBannerToContentStorageClientInterface
{
    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContextByKey(string $contentKey, string $localeName): ?ContentTypeContextTransfer;

    /**
     * Specification:
     * - Retrieves content by keys through a storage client dependency.
     * - Returns the context needed to generate a content type.
     *
     * @api
     *
     * @phpstan-param array<string, string> $contentKeys
     *
     * @phpstan-return array<string, \Generated\Shared\Transfer\ContentTypeContextTransfer>
     *
     * @param string[] $contentKeys
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer[]
     */
    public function getContentTypeContextByKeys(array $contentKeys, string $localeName): array;
}
