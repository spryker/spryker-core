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
     * @deprecated use getDefaultContentTypeAccess() instead.
     *
     * Gets content type access for install (all content types will be created with restricted access)
     *
     * @return bool
     */
    public function getContentTypeAccess(): bool
    {
        return true;
    }

    /**
     * Gets default content type access for install (shopping list content type will be created with restricted access).
     *
     * @return array
     */
    public function getDefaultContentTypeAccess(): array
    {
        return [
            SharedCustomerAccessConfig::CONTENT_TYPE_PRICE => false,
            SharedCustomerAccessConfig::CONTENT_TYPE_ORDER_PLACE_SUBMIT => false,
            SharedCustomerAccessConfig::CONTENT_TYPE_ADD_TO_CART => false,
            SharedCustomerAccessConfig::CONTENT_TYPE_WISHLIST => false,
            SharedCustomerAccessConfig::CONTENT_TYPE_SHOPPING_LIST => true,
        ];
    }
}
