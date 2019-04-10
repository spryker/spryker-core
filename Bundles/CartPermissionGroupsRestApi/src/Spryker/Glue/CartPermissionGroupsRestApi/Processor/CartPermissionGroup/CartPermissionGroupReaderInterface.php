<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CartPermissionGroupReaderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCartPermissionGroupList(): RestResponseInterface;

    /**
     * @param int $idCartPermissionGroup
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findCartPermissionGroupById(int $idCartPermissionGroup): RestResponseInterface;
}
