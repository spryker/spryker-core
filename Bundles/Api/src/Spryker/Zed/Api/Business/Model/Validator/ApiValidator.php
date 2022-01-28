<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Validator;

use Generated\Shared\Transfer\ApiRequestTransfer;

class ApiValidator implements ApiValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface>
     */
    protected $apiValidatorPlugins;

    /**
     * @param array<\Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface> $apiValidatorPlugins
     */
    public function __construct(array $apiValidatorPlugins)
    {
        $this->apiValidatorPlugins = $apiValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer): array
    {
        $resourceName = $apiRequestTransfer->getResourceOrFail();
        foreach ($this->apiValidatorPlugins as $apiValidatorPlugin) {
            if (mb_strtolower($apiValidatorPlugin->getResourceName()) === mb_strtolower($resourceName)) {
                return $apiValidatorPlugin->validate($apiRequestTransfer);
            }
        }

        return [];
    }
}
