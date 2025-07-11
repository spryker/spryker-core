<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Reader\Agent;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

class AgentMultiFactorAuthReader implements AgentMultiFactorAuthReaderInterface
{
    /**
     * @param \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface $client
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $multiFactorAuthPlugins
     */
    public function __construct(
        protected MultiFactorAuthClientInterface $client,
        protected array $multiFactorAuthPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getAvailableAgentMultiFactorAuthTypes(
        MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
    ): MultiFactorAuthTypesCollectionTransfer {
        $configuredMultiFactorAuthTypes = $this->client->getAgentMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        $multiFactorAuthTypes = [];
        foreach ($configuredMultiFactorAuthTypes->getMultiFactorAuthTypes() as $multiFactorAuthTransfer) {
            if ($multiFactorAuthTransfer->getStatus() !== MultiFactorAuthConstants::STATUS_ACTIVE) {
                continue;
            }

            $multiFactorAuthTypes[$multiFactorAuthTransfer->getType()] = $multiFactorAuthTransfer;
        }

        $wiredMultiFactorAuthTypesIndexedByName = [];
        foreach ($this->multiFactorAuthPlugins as $plugin) {
            $wiredMultiFactorAuthTypesIndexedByName[$plugin->getName()] = $plugin;

            if (isset($multiFactorAuthTypes[$plugin->getName()])) {
                continue;
            }

            $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
                ->setType($plugin->getName())
                ->setStatus(MultiFactorAuthConstants::STATUS_INACTIVE)
                ->setUser($multiFactorAuthCriteriaTransfer->getUserOrFail());

            $multiFactorAuthTypes[$plugin->getName()] = $multiFactorAuthTransfer;
        }

        $multiFactorAuthTypes = array_intersect_key($multiFactorAuthTypes, $wiredMultiFactorAuthTypesIndexedByName);
        ksort($multiFactorAuthTypes);
        $multiFactorAuthTypesCollectionTransfer = new MultiFactorAuthTypesCollectionTransfer();

        foreach ($multiFactorAuthTypes as $multiFactorAuthTypeTransfer) {
            $multiFactorAuthTypesCollectionTransfer->addMultiFactorAuth($multiFactorAuthTypeTransfer);
        }

        return $multiFactorAuthTypesCollectionTransfer;
    }

    /**
     * @return bool
     */
    public function isAgentMultiFactorAuthPluginsAvailable(): bool
    {
        return $this->multiFactorAuthPlugins !== [];
    }
}
