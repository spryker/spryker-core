<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Validator;

use Generated\Shared\Transfer\ApiDataTransfer;

class ApiValidator implements ApiValidatorInterface
{
    /**
     * @var \Spryker\Zed\Api\Dependency\Plugin\ApiValidatorPluginInterface[]
     */
    protected $validatorPlugins;

    /**
     * @param \Spryker\Zed\Api\Dependency\Plugin\ApiValidatorPluginInterface[] $validatorPlugins
     */
    public function __construct(array $validatorPlugins)
    {
        $this->validatorPlugins = $validatorPlugins;
    }

    /**
     * @param string $resourceName
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer[]
     */
    public function validate($resourceName, ApiDataTransfer $apiDataTransfer)
    {
        foreach ($this->validatorPlugins as $plugin) {
            if (mb_strtolower($plugin->getResourceName()) === mb_strtolower($resourceName)) {
                return $plugin->validate($apiDataTransfer);
            }
        }

        return [];
    }
}
