<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess;

use Spryker\Shared\CustomerAccess\CustomerAccessConfig as SharedCustomerAccessConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerAccessConfig extends AbstractBundleConfig
{
    /**
     * Gets list of content types for which admin will be able to define permissions
     *
     * @return array
     */
    public function getContentTypes(): array
    {
        return [];
    }

    /**
     * Gets content type access for install.
     *
     * @return bool
     */
    public function getContentTypeAccess(): bool
    {
        return true;
    }

    /**
     * Gets content type access restricted by default for install.
     *
     * @return bool[]
     */
    public function getContentTypeAccessConfiguration(): array
    {
        return [
            SharedCustomerAccessConfig::CONTENT_TYPE_PRICE => true,
            SharedCustomerAccessConfig::CONTENT_TYPE_ORDER_PLACE_SUBMIT => true,
            SharedCustomerAccessConfig::CONTENT_TYPE_ADD_TO_CART => true,
            SharedCustomerAccessConfig::CONTENT_TYPE_WISHLIST => false,
            SharedCustomerAccessConfig::CONTENT_TYPE_SHOPPING_LIST => false,
        ];
    }
}
