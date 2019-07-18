<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business\PermissionFinder;

use ArrayObject;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\PermissionClientInterface;
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
     * @var \Spryker\Client\Permission\PermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @var \Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface[]
     */
    protected $permissionStoragePlugins = [];

    /**
     * @param array $permissionPlugins
     * @param \Spryker\Zed\Permission\Persistence\PermissionRepositoryInterface $permissionRepository
     * @param \Spryker\Client\Permission\PermissionClientInterface $permissionClient
     * @param \Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface[] $permissionStoragePlugins
     */
    public function __construct(
        array $permissionPlugins,
        PermissionRepositoryInterface $permissionRepository,
        PermissionClientInterface $permissionClient,
        array $permissionStoragePlugins
    ) {
        $this->permissionPlugins = $this->indexPermissions($permissionPlugins);
        $this->permissionRepository = $permissionRepository;
        $this->permissionClient = $permissionClient;
        $this->permissionStoragePlugins = $permissionStoragePlugins;
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
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionsByIdentifier(string $identifier): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($this->permissionStoragePlugins as $permissionStoragePlugin) {
            $permissionCollection = $permissionStoragePlugin->getPermissionCollection($identifier);
            foreach ($permissionCollection->getPermissions() as $permission) {
                $permissionCollectionTransfer->addPermission($permission);
            }
        }

        return $permissionCollectionTransfer;
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
    public function findMergedRegisteredNonInfrastructuralPermissions(): PermissionCollectionTransfer
    {
        $registeredPermissions = $this->getIndexedMergedRegisteredNonInfrastructuralPermissions();
        $permissionTransfers = $this->indexPermissionTransfers(
            $this->permissionRepository->findAll()->getPermissions()->getArrayCopy()
        );

        $registeredPermissionTransfers = new ArrayObject();
        foreach ($registeredPermissions as $permissionTransfer) {
            $registeredPermissionTransfers->append($permissionTransfers[$permissionTransfer->getKey()]);
        }

        return (new PermissionCollectionTransfer())
            ->setPermissions($registeredPermissionTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionTransfer[] Keys are permission keys
     */
    protected function getIndexedMergedRegisteredNonInfrastructuralPermissions()
    {
        $zedRegisteredPermissions = $this->getZedRegisteredPermissions();
        $zedRegisteredNonInfrastructuralPermissions = $this->filterNonInfrastructuralPermissions($zedRegisteredPermissions);
        $zedIndexedRegisteredNonInfrastructuralPermissions = $this->indexPermissionTransfers($zedRegisteredNonInfrastructuralPermissions);

        $clientRegisteredPermissions = $this->getClientRegisteredPermissions();
        $clientRegisteredNonInfrastructuralPermissions = $this->filterNonInfrastructuralPermissions($clientRegisteredPermissions);
        $clientIndexedRegisteredNonInfrastructuralPermissions = $this->indexPermissionTransfers($clientRegisteredNonInfrastructuralPermissions);

        return $zedIndexedRegisteredNonInfrastructuralPermissions + $clientIndexedRegisteredNonInfrastructuralPermissions;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer[] $permissions
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    protected function filterNonInfrastructuralPermissions(array $permissions)
    {
        return array_filter($permissions, function (PermissionTransfer $permission) {
            return !$permission->getIsInfrastructural();
        });
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    protected function getZedRegisteredPermissions()
    {
        return $this->getRegisteredPermissions()
            ->getPermissions()
            ->getArrayCopy();
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    protected function getClientRegisteredPermissions()
    {
        return $this->permissionClient->getRegisteredPermissions()
            ->getPermissions()
            ->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer[] $permissions
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer[] Keys are permission keys
     */
    protected function indexPermissionTransfers(
        array $permissions
    ): array {
        $indexedPermissions = [];
        foreach ($permissions as $permission) {
            $indexedPermissions[$permission->getKey()] = $permission;
        }

        return $indexedPermissions;
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
