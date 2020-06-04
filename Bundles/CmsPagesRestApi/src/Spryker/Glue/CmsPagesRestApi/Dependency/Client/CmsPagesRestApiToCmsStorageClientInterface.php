<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Dependency\Client;

interface CmsPagesRestApiToCmsStorageClientInterface
{
    /**
     * @param string[] $cmsPageUuids
     * @param string $mappingType
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPageStorageByUuids(array $cmsPageUuids, string $mappingType, string $localeName, string $storeName): array;
}
