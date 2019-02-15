<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Dependency\Client;

interface ContentBannersRestApiToContentStorageClientInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return array|null
     */
    public function findContentStorageData(int $idContent, string $localeName): ?array;
}
