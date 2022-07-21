<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage\Mapper;

interface CmsPageStorageMapperInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    public function mapCmsPageStorageData(array $data);
}
