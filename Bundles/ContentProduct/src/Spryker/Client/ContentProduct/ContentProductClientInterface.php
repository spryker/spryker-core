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
     * - Finds content item in the key-value storage.
     * - Resolves content type and executes data.
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function getContentProductAbstractListType(int $idContent, string $localeName): ?ContentProductAbstractListTypeTransfer;
}
