<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission;

class CustomerAccessPermissionConfig
{
    /**
     * Convention is SEE_{content type}_PLUGIN for constant name and the value is the key that would be used as a
     * permission key can('SeePrice') as an example
     */
    const SEE_PRICE_PLUGIN = 'SeePrice';

    /**
     * @param string $contentType
     *
     * @return string
     */
    public function getPluginNameToSeeContentType($contentType)
    {
        $constantName = $this->getConstantNameFromContentType($contentType);

        if (defined(CustomerAccessPermissionConfig::class . '::' . $constantName)) {
            return constant(CustomerAccessPermissionConfig::class . '::' . $constantName);
        }

        return '';
    }

    /**
     * @param string $contentType
     *
     * @return string
     */
    protected function getConstantNameFromContentType($contentType)
    {
        return 'SEE_' . strtoupper($contentType) . '_PLUGIN';
    }
}
