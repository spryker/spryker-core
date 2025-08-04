<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Form\DataProvider\Agent;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;
use Spryker\Yves\MultiFactorAuth\Controller\AgentMultiFactorAuthFlowController;

class AgentTypeSelectionFormDataProvider
{
    /**
     * @param \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface $multiFactorAuthClient
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $userMultiFactorAuthPlugins
     */
    public function __construct(
        protected MultiFactorAuthClientInterface $multiFactorAuthClient,
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
            AgentMultiFactorAuthFlowController::TYPES => $this->getEnabledTypes($userTransfer),
            AgentMultiFactorAuthFlowController::IS_ACTIVATION => false,
            AgentMultiFactorAuthFlowController::IS_DEACTIVATION => false,
            AgentMultiFactorAuthFlowController::TYPE_TO_SET_UP => null,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return array<int, string>
     */
    protected function getEnabledTypes(UserTransfer $userTransfer): array
    {
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())->setUser($userTransfer);
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient->getAgentMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);
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
