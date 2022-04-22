<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence\Exception;

use Exception;

class AssetStorageEntityNotFound extends Exception
{
    /**
     * @param int $idAssetSlotStorage
     */
    public function __construct(int $idAssetSlotStorage)
    {
        $message = sprintf('No asset storage entity with id %s', $idAssetSlotStorage);

        parent::__construct($message);
    }
}
