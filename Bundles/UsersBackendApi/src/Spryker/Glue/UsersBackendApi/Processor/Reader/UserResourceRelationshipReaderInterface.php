<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

interface UserResourceRelationshipReaderInterface
{
    /**
     * @param array<int, string> $userUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getUserRelationshipsIndexedByUserUuid(array $userUuids): array;
}
