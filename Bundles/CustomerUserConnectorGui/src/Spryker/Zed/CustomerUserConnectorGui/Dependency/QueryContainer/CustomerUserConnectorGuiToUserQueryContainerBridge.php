<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer;

class CustomerUserConnectorGuiToUserQueryContainerBridge implements CustomerUserConnectorGuiToUserQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected $userQueryContainer;

    /**
     * @param \Spryker\Zed\User\Persistence\UserQueryContainerInterface $userQueryContainer
     */
    public function __construct($userQueryContainer)
    {
        $this->userQueryContainer = $userQueryContainer;
    }

    /**
     * @param int $id
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUserById($id)
    {
        return $this->userQueryContainer->queryUserById($id);
    }
}
