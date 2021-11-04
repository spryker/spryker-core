<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Writer;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Spryker\Zed\Acl\Business\Exception\GroupExistsException;
use Spryker\Zed\Acl\Persistence\AclEntityManagerInterface;
use Spryker\Zed\Acl\Persistence\AclRepositoryInterface;

class GroupWriter implements GroupWriterInterface
{
    /**
     * @var \Spryker\Zed\Acl\Persistence\AclEntityManagerInterface
     */
    protected $aclEntityManager;

    /**
     * @var \Spryker\Zed\Acl\Persistence\AclRepositoryInterface
     */
    protected $aclRepository;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclEntityManagerInterface $aclEntityManager
     * @param \Spryker\Zed\Acl\Persistence\AclRepositoryInterface $aclRepository
     */
    public function __construct(
        AclEntityManagerInterface $aclEntityManager,
        AclRepositoryInterface $aclRepository
    ) {
        $this->aclEntityManager = $aclEntityManager;
        $this->aclRepository = $aclRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupExistsException
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function createGroup(GroupTransfer $groupTransfer): GroupTransfer
    {
        $groupCriteriaTransfer = (new GroupCriteriaTransfer())
            ->fromArray($groupTransfer->toArray(), true);

        $existedGroupTransfer = $this->aclRepository->findGroup($groupCriteriaTransfer);

        if ($existedGroupTransfer) {
            throw new GroupExistsException(
                $this->createGroupExceptionMessage($existedGroupTransfer),
            );
        }

        return $this->aclEntityManager->createGroup($groupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     *
     * @return string
     */
    protected function createGroupExceptionMessage(GroupTransfer $groupTransfer): string
    {
        $exceptionMesaageParameters = [];

        foreach ($groupTransfer->toArray() as $key => $value) {
            if (!$value) {
                continue;
            }

            $exceptionMesaageParameters[] = sprintf('%s: %s', $key, $value);
        }

        $exceptionMesaage = sprintf('Group with %s already exists', implode(' or ', $exceptionMesaageParameters));

        return $exceptionMesaage;
    }
}
