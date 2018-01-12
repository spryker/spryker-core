<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage\Mapper;

use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;

class CmsPageStorageMapper implements CmsPageStorageMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    public function mapCmsPageStorageData(array $data)
    {
        $localeCmsPageDataTransfer = new LocaleCmsPageDataTransfer();
        $localeCmsPageDataTransfer->fromArray($data, true);

        return $localeCmsPageDataTransfer;
    }
}
