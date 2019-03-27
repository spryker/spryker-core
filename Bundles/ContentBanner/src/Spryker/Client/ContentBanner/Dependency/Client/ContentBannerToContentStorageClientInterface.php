<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Dependency\Client;

interface ContentBannerToContentStorageClientInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\UnexecutedContentStorageTransfer|null
     */
    public function findUnexecutedContentById(int $idContent, string $localeName);
}
