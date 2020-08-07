<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;

interface ContentProductClientInterface
{
    /**
     * Specification:
     * - Finds content item in the key-value storage by content key and locale name.
     * - Gets stored term for found content item.
     * - Executes stored term with found content item to get a content product abstract list type.
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function executeProductAbstractListTypeByKey(string $contentKey, string $localeName): ?ContentProductAbstractListTypeTransfer;

    /**
     * Specification:
     * - Finds content items in the key-value storage by content keys and locale name.
     * - Gets stored term for found content items.
     * - Executes stored term with found content items to get a collection of content product abstract list type.
     *
     * @api
     *
     * @phpstan-return array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer>
     *
     * @param string[] $contentKeys
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer[]
     */
    public function executeProductAbstractListTypeByKeys(array $contentKeys, string $localeName): array;
}
