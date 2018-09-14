<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business\PermissionFinder;

use ArrayObject;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\InfrastructuralPermissionPluginInterface;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\Permission\Persistence\PermissionRepositoryInterface;

class PermissionFinder implements PermissionFinderInterface
{
    /**
     * @var \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface[]
     */
    protected $permissionPlugins = [];

    /**
     * @var \Spryker\Zed\Permission\Persistence\PermissionRepositoryInterface
     */
    protected $permissionRepository;

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface[] $permissionPlugins
     * @param \Spryker\Zed\Permission\Persistence\PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(array $permissionPlugins, PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionPlugins = $this->indexPermissions($permissionPlugins);
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param string $permissionKey
     *
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface|null
     */
    public function findPermissionPlugin($permissionKey): ?PermissionPluginInterface
    {
        if (!isset($this->permissionPlugins[$permissionKey])) {
            return null;
        }

        return $this->permissionPlugins[$permissionKey];
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getRegisteredPermissions(): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($this->permissionPlugins as $permissionPlugin) {
            $permissionTransfer = (new PermissionTransfer())
                ->setKey($permissionPlugin->getKey());

            if ($permissionPlugin instanceof ExecutablePermissionPluginInterface) {
                $permissionTransfer->setConfigurationSignature($permissionPlugin->getConfigurationSignature());
            }

            $permissionTransfer->setIsInfrastructural(
                $permissionPlugin instanceof InfrastructuralPermissionPluginInterface
            );

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getRegisteredNonInfrastructuralPermissions(): PermissionCollectionTransfer
    {
        $availablePermissions = $this->permissionRepository->findAll()->getPermissions();

        $nonInfrastructuralPermissions = new ArrayObject();
        foreach ($availablePermissions as $permissionTransfer) {
            if (!$permissionTransfer->getIsInfrastructural()) {
                $nonInfrastructuralPermissions->append($permissionTransfer);
            }
        }

        return (new PermissionCollectionTransfer())
            ->setPermissions($nonInfrastructuralPermissions);
    }

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface[] $permissionPlugins
     *
     * @return array
     */
    protected function indexPermissions(array $permissionPlugins): array
    {
        $plugins = [];

        foreach ($permissionPlugins as $permissionPlugin) {
            $plugins[$permissionPlugin->getKey()] = $permissionPlugin;
        }

        return $plugins;
    }
}
