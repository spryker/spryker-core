<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;
use Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface;
use Spryker\Glue\UsersBackendApi\Processor\Mapper\UserResourceMapperInterface;

class UserResourceReader implements UserResourceReaderInterface
{
    /**
     * @var \Spryker\Glue\UsersBackendApi\Processor\Mapper\UserResourceMapperInterface
     */
    protected UserResourceMapperInterface $userResourceMapper;

    /**
     * @var \Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface
     */
    protected UsersBackendApiToUserFacadeInterface $userFacade;

    /**
     * @param \Spryker\Glue\UsersBackendApi\Processor\Mapper\UserResourceMapperInterface $userResourceMapper
     * @param \Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface $userFacade
     */
    public function __construct(
        UserResourceMapperInterface $userResourceMapper,
        UsersBackendApiToUserFacadeInterface $userFacade
    ) {
        $this->userResourceMapper = $userResourceMapper;
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResourceCollection(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer
    {
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $this->userResourceMapper->mapUserCollectionToUserResourceCollection(
            $userCollectionTransfer,
            new UserResourceCollectionTransfer(),
        );
    }
}
