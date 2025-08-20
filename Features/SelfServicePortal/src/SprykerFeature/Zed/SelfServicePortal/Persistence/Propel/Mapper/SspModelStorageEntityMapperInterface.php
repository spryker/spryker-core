<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelStorage;

interface SspModelStorageEntityMapperInterface
{
    public function mapSspModelTransferToSspModelStorageEntity(
        SspModelTransfer $sspModelTransfer,
        SpySspModelStorage $sspModelStorageEntity
    ): SpySspModelStorage;
}
