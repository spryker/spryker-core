<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Reader;

interface PickingListWarehouseResourceRelationshipReaderInterface
{
    /**
     * @param list<string> $pickingListUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getWarehouseRelationshipsIndexedByPickingListUuid(array $pickingListUuids): array;
}
