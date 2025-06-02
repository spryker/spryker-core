<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Form\DataProvider\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;

class CustomerTypeSelectionFormDataProvider
{
    /**
     * @var string
     */
    protected const OPTIONS_TYPES = 'types';

    /**
     * @var string
     */
    protected const OPTION_EMAIL = 'email';

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
            static::OPTIONS_TYPES => $this->getEnabledTypes($customerTransfer),
            static::OPTION_EMAIL => $customerTransfer->getEmail(),
            static::FIELD_IS_ACTIVATION => false,
            static::FIELD_IS_DEACTIVATION => false,
            static::FIELD_TYPE_TO_SET_UP => null,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array<int, string>
     */
    protected function getEnabledTypes(CustomerTransfer $customerTransfer): array
    {
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient->getCustomerMultiFactorAuthTypes($customerTransfer);
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
