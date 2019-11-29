<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnector\Dependency\QueryContainer;

interface CustomerUserConnectorToUserQueryContainerInterface
{
    /**
     * @param int $id
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUserById($id);
}
