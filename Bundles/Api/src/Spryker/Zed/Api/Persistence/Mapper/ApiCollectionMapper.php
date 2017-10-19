<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence\Mapper;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ApiCollectionMapper implements ApiCollectionMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function toCollection(array $data)
    {
        $apiCollectionTransfer = new ApiCollectionTransfer();

        $collectionData = [];
        foreach ($data as $item) {
            if ($item instanceof AbstractTransfer) {
                $collectionData[] = $item->modifiedToArray();
            } else {
                $collectionData[] = $item;
            }
        }

        $apiCollectionTransfer->setData($collectionData);

        return $apiCollectionTransfer;
    }
}
