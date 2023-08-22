<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;
use Generated\Shared\Transfer\UsersRestAttributesTransfer;
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
    public function getUsersResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer
    {
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $this->userResourceMapper->mapUserCollectionToUsersResourceCollection(
            $userCollectionTransfer,
            new UserResourceCollectionTransfer(),
        );
    }

    /**
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceReader::getUsersResources()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer
    {
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $this->userResourceMapper->mapUserCollectionToUserResourceCollection(
            $userCollectionTransfer,
            new UserResourceCollectionTransfer(),
        );
    }

    /**
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceReader::getUserResources()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResourceCollection(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer
    {
        $userResourceCollectionTransfer = $this->getUserResources($userCriteriaTransfer);

        foreach ($userResourceCollectionTransfer->getUserResources() as $userResource) {
            /** @var \Generated\Shared\Transfer\ApiUsersAttributesTransfer $apiUsersAttributesTransfer */
            $apiUsersAttributesTransfer = $userResource->getAttributesOrFail();
            $usersRestAttributesTransfer = $this->userResourceMapper->mapApiUsersAttributesTransferToUsersRestAttributesTransfer(
                $apiUsersAttributesTransfer,
                new UsersRestAttributesTransfer(),
            );
            $userResource->setAttributes($usersRestAttributesTransfer);
        }

        return $userResourceCollectionTransfer;
    }
}
