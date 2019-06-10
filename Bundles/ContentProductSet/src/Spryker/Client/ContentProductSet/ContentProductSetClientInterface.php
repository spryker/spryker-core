<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProductSet;

use Generated\Shared\Transfer\ContentProductSetTypeTransfer;

interface ContentProductSetClientInterface
{
    /**
     * Specification:
     * - Finds content item in the key-value storage by content ID and locale name.
     * - Gets stored term for found content item.
     * - Executes stored term with found content item to get a content product set type.
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductSetTypeTransfer|null
     */
    public function executeProductSetTypeByKey(string $contentKey, string $localeName): ?ContentProductSetTypeTransfer;
}
