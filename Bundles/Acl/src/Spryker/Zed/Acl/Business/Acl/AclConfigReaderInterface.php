<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Acl;

interface AclConfigReaderInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\RoleTransfer>
     */
    public function getRoles(): array;

    /**
     * @return array<\Generated\Shared\Transfer\GroupTransfer>
     */
    public function getGroups(): array;

    /**
     * @return array<\Generated\Shared\Transfer\UserTransfer>
     */
    public function getUserGroupRelations(): array;
}
