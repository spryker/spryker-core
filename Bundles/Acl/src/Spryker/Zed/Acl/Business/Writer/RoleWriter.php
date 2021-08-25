<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Writer;

use Generated\Shared\Transfer\AclRoleCriteriaTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\Acl\Business\Exception\RoleExistsException;
use Spryker\Zed\Acl\Persistence\AclEntityManagerInterface;
use Spryker\Zed\Acl\Persistence\AclRepositoryInterface;

class RoleWriter implements RoleWriterInterface
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
     * @var \Spryker\Zed\AclExtension\Dependency\Plugin\AclRolePostSavePluginInterface[]
     */
    protected $aclRolePostSavePlugins;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclEntityManagerInterface $aclEntityManager
     * @param \Spryker\Zed\Acl\Persistence\AclRepositoryInterface $aclRepository
     * @param \Spryker\Zed\AclExtension\Dependency\Plugin\AclRolePostSavePluginInterface[] $aclRolePostSavePlugins
     */
    public function __construct(
        AclEntityManagerInterface $aclEntityManager,
        AclRepositoryInterface $aclRepository,
        array $aclRolePostSavePlugins
    ) {
        $this->aclEntityManager = $aclEntityManager;
        $this->aclRepository = $aclRepository;
        $this->aclRolePostSavePlugins = $aclRolePostSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleExistsException
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function createRole(RoleTransfer $roleTransfer): RoleTransfer
    {
        $aclRoleCriteriaTransfer = (new AclRoleCriteriaTransfer())
            ->fromArray($roleTransfer->toArray(), true);

        $existedRoleTransfer = $this->aclRepository->findRole($aclRoleCriteriaTransfer);

        if ($existedRoleTransfer) {
            throw new RoleExistsException(
                $this->createRoleExceptionMessage($existedRoleTransfer)
            );
        }

        $roleTransfer = $this->aclEntityManager->createRole($roleTransfer);
        $roleTransfer = $this->executeAclRolPostSavePlugins($roleTransfer);

        return $roleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return string
     */
    protected function createRoleExceptionMessage(RoleTransfer $roleTransfer): string
    {
        $exceptionMesaageParameters = [];

        foreach ($roleTransfer->toArray() as $key => $value) {
            if (!$value) {
                continue;
            }

            $exceptionMesaageParameters[] = sprintf('%s: %s', $key, $value);
        }

        $exceptionMesaage = sprintf('Role with %s already exists', implode(' or ', $exceptionMesaageParameters));

        return $exceptionMesaage;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    protected function executeAclRolPostSavePlugins(RoleTransfer $roleTransfer): RoleTransfer
    {
        foreach ($this->aclRolePostSavePlugins as $aclRolePostSavePlugin) {
            $roleTransfer = $aclRolePostSavePlugin->postSave($roleTransfer);
        }

        return $roleTransfer;
    }
}
