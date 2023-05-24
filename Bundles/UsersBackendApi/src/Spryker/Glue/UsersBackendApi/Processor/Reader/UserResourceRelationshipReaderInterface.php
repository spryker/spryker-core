<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

interface UserResourceRelationshipReaderInterface
{
    /**
     * @param list<string> $userUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getUserRelationshipsIndexedByUserUuid(array $userUuids): array;

    /**
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceRelationshipReaderInterface::getUserRelationshipsWithUsersRestAttributesIndexedByUserUuid()} instead.
     *
     * @param list<string> $userUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getUserRelationshipsWithUsersRestAttributesIndexedByUserUuid(array $userUuids): array;
}
