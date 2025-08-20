<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Storage\Mapper;

use Generated\Shared\Transfer\SspModelStorageTransfer;

interface SspModelStorageMapperInterface
{
    /**
     * @param array<string, mixed> $storageData
     *
     * @return \Generated\Shared\Transfer\SspModelStorageTransfer
     */
    public function mapStorageDataToSspModelStorageTransfer(array $storageData): SspModelStorageTransfer;
}
