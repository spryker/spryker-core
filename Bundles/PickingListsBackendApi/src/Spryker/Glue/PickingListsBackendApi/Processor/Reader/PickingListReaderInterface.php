<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListTransfer;

interface PickingListReaderInterface
{
    /**
     * @param string $pickingListUuid
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function getPickingListByPickingListUuid(string $pickingListUuid): PickingListTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getPickingListCollection(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;

    /**
     * @param string $uuid
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getPickingList(
        string $uuid,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;

    /**
     * @param list<string> $pickingListUuids
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getPickingListCollectionByPickingListUuids(array $pickingListUuids): PickingListCollectionTransfer;
}
