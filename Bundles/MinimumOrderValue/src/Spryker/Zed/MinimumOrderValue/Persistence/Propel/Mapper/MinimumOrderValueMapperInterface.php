<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueType;

interface MinimumOrderValueMapperInterface
{
    /**
     * @param \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueType $spyMinimumOrderValueType
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function mapMinimumOrderValueTypeEntityToTransfer(
        SpyMinimumOrderValueType $spyMinimumOrderValueType,
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer;

    /**
     * @param \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValue $minimumOrderValueEntity
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function mapGlobalMinimumOrderValueEntityToTransfer(
        SpyMinimumOrderValue $minimumOrderValueEntity,
        GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
    ): GlobalMinimumOrderValueTransfer;
}
