<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;

class UserMapper implements UserMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function mapGlueRequestTransferToUserTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        UserTransfer $userTransfer
    ): UserTransfer {
        $requestUser = $glueRequestTransfer->getRequestUser();
        if ($requestUser === null) {
            return $userTransfer;
        }

        $userUuid = $requestUser->getNaturalIdentifier();
        if ($userUuid === null) {
            return $userTransfer;
        }

        return $userTransfer->setUuid($userUuid);
    }
}
