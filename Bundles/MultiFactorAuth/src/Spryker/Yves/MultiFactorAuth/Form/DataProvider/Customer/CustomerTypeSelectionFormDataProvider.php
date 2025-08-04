<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Form\DataProvider\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;
use Spryker\Yves\MultiFactorAuth\Controller\CustomerMultiFactorAuthFlowController;

class CustomerTypeSelectionFormDataProvider
{
    /**
     * @param \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface $multiFactorAuthClient
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $customerMultiFactorAuthPlugins
     */
    public function __construct(
        protected MultiFactorAuthClientInterface $multiFactorAuthClient,
        protected array $customerMultiFactorAuthPlugins = []
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(CustomerTransfer $customerTransfer): array
    {
        return [
            CustomerMultiFactorAuthFlowController::TYPES => $this->getEnabledTypes($customerTransfer),
            CustomerMultiFactorAuthFlowController::IS_ACTIVATION => false,
            CustomerMultiFactorAuthFlowController::IS_DEACTIVATION => false,
            CustomerMultiFactorAuthFlowController::TYPE_TO_SET_UP => null,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array<int, string>
     */
    protected function getEnabledTypes(CustomerTransfer $customerTransfer): array
    {
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())
            ->setCustomer($customerTransfer);
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient->getCustomerMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);
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
        foreach ($this->customerMultiFactorAuthPlugins as $plugin) {
            $indexedPlugins[$plugin->getName()] = $plugin;
        }

        return $indexedPlugins;
    }
}
