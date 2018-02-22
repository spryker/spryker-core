<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\EntityManager;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;

interface TransferToEntityMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    public function mapEntityCollection(EntityTransferInterface $entityTransfer);

    /**
     * @param string $transferClassName
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $parentEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function mapTransferCollection($transferClassName, ActiveRecordInterface $parentEntity);
}
