<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsBlockProductConnector;

use Spryker\Shared\CmsBlock\CmsBlockConfig;

interface CmsBlockProductConnectorConstants
{

    const OPTION_NAME = 'product_abstract';
    const RESOURCE_TYPE_CMS_BLOCK_PRODUCT_CONNECTOR = CmsBlockConfig::RESOURCE_TYPE_CMS_BLOCK . '.' . self::OPTION_NAME;

}
