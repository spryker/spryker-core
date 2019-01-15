<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper;

use Generated\Shared\Transfer\DummyEntityTransfer;
use Generated\Shared\Transfer\RestDummyEntityAttributesTransfer;

class DummyEntityMapper implements DummyEntityMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\DummyEntityTransfer $dummyEntityTransfer
     * @param \Generated\Shared\Transfer\RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestDummyEntityAttributesTransfer
     */
    public function mapDummyEntityTransferToRestDummyEntityAttributesTransfer(
        DummyEntityTransfer $dummyEntityTransfer,
        RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
    ): RestDummyEntityAttributesTransfer {
        $restDummyEntityAttributesTransfer->fromArray($dummyEntityTransfer->toArray(), true);

        return $restDummyEntityAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
     * @param \Generated\Shared\Transfer\DummyEntityTransfer $dummyEntityTransfer
     *
     * @return \Generated\Shared\Transfer\DummyEntityTransfer
     */
    public function mapRestDummyEntityAttributesTransferToDummyEntityTransfer(
        RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer,
        DummyEntityTransfer $dummyEntityTransfer
    ): DummyEntityTransfer {
        $dummyEntityTransfer->fromArray($restDummyEntityAttributesTransfer->toArray(), true);

        return $dummyEntityTransfer;
    }
}
