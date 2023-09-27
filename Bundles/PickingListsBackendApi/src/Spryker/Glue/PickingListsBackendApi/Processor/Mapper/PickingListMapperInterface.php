<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\PickingListsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListTransfer;

interface PickingListMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function mapGlueRequestTransferToPickingListTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PickingListTransfer $pickingListTransfer
    ): PickingListTransfer;

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Generated\Shared\Transfer\PickingListsBackendApiAttributesTransfer $pickingListsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListsBackendApiAttributesTransfer
     */
    public function mapPickingListTransferToPickingListsBackendApiAttributesTransfer(
        PickingListTransfer $pickingListTransfer,
        PickingListsBackendApiAttributesTransfer $pickingListsBackendApiAttributesTransfer
    ): PickingListsBackendApiAttributesTransfer;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransferCollection
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function mapPickingListItemGlueResourceTransferCollectionToPickingListTransfer(
        ArrayObject $glueResourceTransferCollection,
        PickingListTransfer $pickingListTransfer
    ): PickingListTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PickingListConditionsTransfer $pickingListConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListConditionsTransfer
     */
    public function mapGlueRequestTransferToPickingListConditionsTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PickingListConditionsTransfer $pickingListConditionsTransfer
    ): PickingListConditionsTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCriteriaTransfer
     */
    public function mapGlueRequestTransferToPickingListCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCriteriaTransfer;
}
