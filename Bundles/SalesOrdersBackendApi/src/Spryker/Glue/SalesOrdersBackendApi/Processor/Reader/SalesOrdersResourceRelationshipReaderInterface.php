<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi\Processor\Reader;

interface SalesOrdersResourceRelationshipReaderInterface
{
    /**
     * @param list<string> $orderItemUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getOrderRelationshipsIndexedByOrderItemUuid(array $orderItemUuids): array;
}
