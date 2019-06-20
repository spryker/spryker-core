<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProductSet\Dependency\Client;

use Generated\Shared\Transfer\ContentTypeContextTransfer;

interface ContentProductSetToContentStorageClientInterface
{
    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContextByKey(string $contentKey, string $localeName): ?ContentTypeContextTransfer;
}
