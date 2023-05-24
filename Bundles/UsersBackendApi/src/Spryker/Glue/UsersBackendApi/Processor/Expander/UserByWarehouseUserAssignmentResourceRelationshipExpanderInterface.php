<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Expander;

interface UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return void
     */
    public function addUserRelationships(array $glueResourceTransfers): void;

    /**
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\Processor\Expander\UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface::addUserRelationships()} instead.
     *
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return void
     */
    public function addUserRelationshipsWithUsersRestAttributes(array $glueResourceTransfers): void;
}
