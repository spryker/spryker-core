<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface;

class WarehouseUserAssignmentValidator implements WarehouseUserAssignmentValidatorInterface
{
    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface
     */
    protected UserReaderInterface $userReader;

    /**
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface $userReader
     */
    public function __construct(UserReaderInterface $userReader)
    {
        $this->userReader = $userReader;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param string $warehouseUserAssignmentUserUuid
     *
     * @return bool
     */
    public function isCurrentUserAllowedToOperateWithWarehouseUserAssignment(
        GlueRequestTransfer $glueRequestTransfer,
        string $warehouseUserAssignmentUserUuid
    ): bool {
        $userTransfer = $this->userReader->findUserTransferById($glueRequestTransfer->getRequestUserOrFail()->getSurrogateIdentifierOrFail());

        return $userTransfer && (!$userTransfer->getIsWarehouseUser() || $userTransfer->getUuidOrFail() === $warehouseUserAssignmentUserUuid);
    }
}
