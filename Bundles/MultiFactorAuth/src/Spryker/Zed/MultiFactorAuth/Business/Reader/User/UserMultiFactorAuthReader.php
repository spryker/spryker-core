<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Reader\User;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

class UserMultiFactorAuthReader implements UserMultiFactorAuthReaderInterface
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface $repository
     */
    public function __construct(protected MultiFactorAuthRepositoryInterface $repository)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $userMultiFactorAuthPlugins
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getAvailableUserMultiFactorAuthTypes(
        MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer,
        array $userMultiFactorAuthPlugins
    ): MultiFactorAuthTypesCollectionTransfer {
        $multiFactorAuthTypes = $this->getActiveConfiguredMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        $multiFactorAuthTypes = $this->enrichWithWiredMultiFactorAuthTypes(
            $multiFactorAuthTypes,
            $multiFactorAuthCriteriaTransfer->getUserOrFail(),
            $userMultiFactorAuthPlugins,
        );

        return $this->mapToMultiFactorAuthTypesCollectionTransfer($multiFactorAuthTypes);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\MultiFactorAuthTransfer>
     */
    protected function getActiveConfiguredMultiFactorAuthTypes(MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer): array
    {
        $configuredTypes = $this->repository->getUserMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);
        $activeTypes = [];

        foreach ($configuredTypes->getMultiFactorAuthTypes() as $multiFactorAuthTransfer) {
            if ($multiFactorAuthTransfer->getStatus() === MultiFactorAuthConstants::STATUS_ACTIVE) {
                $activeTypes[$multiFactorAuthTransfer->getType()] = $multiFactorAuthTransfer;
            }
        }

        return $activeTypes;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\MultiFactorAuthTransfer> $multiFactorAuthTypes
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $userMultiFactorAuthPlugins
     *
     * @return array<string, \Generated\Shared\Transfer\MultiFactorAuthTransfer>
     */
    protected function enrichWithWiredMultiFactorAuthTypes(
        array $multiFactorAuthTypes,
        UserTransfer $userTransfer,
        array $userMultiFactorAuthPlugins
    ): array {
        $wiredPlugins = [];

        foreach ($userMultiFactorAuthPlugins as $userMultiFactorAuthPlugin) {
            $wiredPlugins[$userMultiFactorAuthPlugin->getName()] = $userMultiFactorAuthPlugin;

            if (isset($multiFactorAuthTypes[$userMultiFactorAuthPlugin->getName()])) {
                continue;
            }

            $multiFactorAuthTypes[$userMultiFactorAuthPlugin->getName()] = (new MultiFactorAuthTransfer())
                ->setType($userMultiFactorAuthPlugin->getName())
                ->setStatus(MultiFactorAuthConstants::STATUS_INACTIVE)
                ->setUser($userTransfer);
        }

        return array_intersect_key($multiFactorAuthTypes, $wiredPlugins);
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\MultiFactorAuthTransfer> $multiFactorAuthTypes
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    protected function mapToMultiFactorAuthTypesCollectionTransfer(array $multiFactorAuthTypes): MultiFactorAuthTypesCollectionTransfer
    {
        ksort($multiFactorAuthTypes);

        $collectionTransfer = new MultiFactorAuthTypesCollectionTransfer();
        foreach ($multiFactorAuthTypes as $multiFactorAuthTransfer) {
            $collectionTransfer->addMultiFactorAuth($multiFactorAuthTransfer);
        }

        return $collectionTransfer;
    }
}
