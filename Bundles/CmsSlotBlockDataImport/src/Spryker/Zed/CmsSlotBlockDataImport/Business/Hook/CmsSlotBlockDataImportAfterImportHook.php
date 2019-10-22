<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\Hook;

use Spryker\Zed\CmsSlotBlock\Dependency\CmsSlotBlockEvents;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;

class CmsSlotBlockDataImportAfterImportHook implements DataImporterAfterImportInterface
{
    public const ID_DEFAULT = 0;

    /**
     * @return void
     */
    public function afterImport(): void
    {
        DataImporterPublisher::addEvent(CmsSlotBlockEvents::CMS_SLOT_BLOCK_PUBLISH, static::ID_DEFAULT);
    }
}
