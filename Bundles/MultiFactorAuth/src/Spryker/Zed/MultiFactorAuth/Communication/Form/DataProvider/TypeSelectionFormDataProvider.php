<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

class TypeSelectionFormDataProvider
{
    /**
     * @var string
     */
    protected const OPTION_TYPES = 'types';

    /**
     * @var string
     */
    protected const FIELD_IS_ACTIVATION = 'is_activation';

    /**
     * @var string
     */
    protected const FIELD_IS_DEACTIVATION = 'is_deactivation';

    /**
     * @var string
     */
    protected const FIELD_TYPE_TO_SET_UP = 'type_to_set_up';

    /**
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface $repository
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $userMultiFactorAuthPlugins
     */
    public function __construct(
        protected MultiFactorAuthRepositoryInterface $repository,
        protected array $userMultiFactorAuthPlugins = []
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(UserTransfer $userTransfer): array
    {
        return [
            static::OPTION_TYPES => $this->getEnabledTypes($userTransfer),
            static::FIELD_IS_ACTIVATION => false,
            static::FIELD_IS_DEACTIVATION => false,
            static::FIELD_TYPE_TO_SET_UP => null,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return array<int, string>
     */
    protected function getEnabledTypes(UserTransfer $userTransfer): array
    {
        $multiFactorAuthCriteraTransfer = (new MultiFactorAuthCriteriaTransfer())
            ->setUser($userTransfer);

        $multiFactorAuthTypesCollectionTransfer = $this->repository->getUserMultiFactorAuthTypes($multiFactorAuthCriteraTransfer);
        $multiFactorAuthTypePluginsIndexedByName = $this->indexMultiFactorAuthPluginsByName();
        $enabledTypes = [];

        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthTypeTransfer) {
            if (!isset($multiFactorAuthTypePluginsIndexedByName[$multiFactorAuthTypeTransfer->getType()])) {
                continue;
            }

            $enabledTypes[] = $multiFactorAuthTypeTransfer->getTypeOrFail();
        }

        return $enabledTypes;
    }

    /**
     * @return array<string, \Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    protected function indexMultiFactorAuthPluginsByName(): array
    {
        $indexedPlugins = [];
        foreach ($this->userMultiFactorAuthPlugins as $plugin) {
            $indexedPlugins[$plugin->getName()] = $plugin;
        }

        return $indexedPlugins;
    }
}
