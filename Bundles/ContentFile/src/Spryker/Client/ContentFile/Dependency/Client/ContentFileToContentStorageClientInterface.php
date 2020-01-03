<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile\Dependency\Client;

use Generated\Shared\Transfer\ContentTypeContextTransfer;

interface ContentFileToContentStorageClientInterface
{
    /**
     * @param string $contentKey
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContextByKey(string $contentKey, string $locale): ?ContentTypeContextTransfer;
}
