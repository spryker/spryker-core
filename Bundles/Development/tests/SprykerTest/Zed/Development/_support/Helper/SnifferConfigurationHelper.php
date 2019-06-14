<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Helper;

use Codeception\Module;

class SnifferConfigurationHelper extends Module
{
    protected const PATH_SPRYKER_ZED_ACL_MODULE = 'ConfigurationReader/Spryker/Zed/Acl/';
    protected const PATH_SPRYKER_ZED_CUSTOMER_MODULE = 'ConfigurationReader/Spryker/Zed/Customer/';
    protected const PATH_SPRYKER_ZED_COUNTRY_MODULE = 'ConfigurationReader/Spryker/Zed/Country/';
    protected const PATH_SPRYKER_ZED_DISCOUNT_MODULE = 'ConfigurationReader/Spryker/Zed/Discount/';
    protected const PATH_SPRYKER_ZED_PRODUCT_MODULE = 'ConfigurationReader/Spryker/Zed/Product/';
    protected const PATH_CUSTOM_FOLDER = 'ConfigurationReader/custom/';

    /**
     * @return string
     */
    public function getZedAclModulePath(): string
    {
        return $this->getModuleAbsolutePath(static::PATH_SPRYKER_ZED_ACL_MODULE);
    }

    /**
     * @return string
     */
    public function getZedCustomerModulePath(): string
    {
        return $this->getModuleAbsolutePath(static::PATH_SPRYKER_ZED_CUSTOMER_MODULE);
    }

    /**
     * @return string
     */
    public function getZedDiscountModulePath(): string
    {
        return $this->getModuleAbsolutePath(static::PATH_SPRYKER_ZED_DISCOUNT_MODULE);
    }

    /**
     * @return string
     */
    public function getZedProductModulePath(): string
    {
        return $this->getModuleAbsolutePath(static::PATH_SPRYKER_ZED_PRODUCT_MODULE);
    }

    /**
     * @return string
     */
    public function getZedCustomPath(): string
    {
        return $this->getModuleAbsolutePath(static::PATH_CUSTOM_FOLDER);
    }

    /**
     * @return string
     */
    public function getZedCountryPath(): string
    {
        return $this->getModuleAbsolutePath(static::PATH_SPRYKER_ZED_COUNTRY_MODULE);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getModuleAbsolutePath(string $path): string
    {
        return codecept_data_dir() . $path;
    }
}
