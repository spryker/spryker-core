<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class StoresApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_STORES = 'stores';

    /**
     * @var string
     */
    public const RESPONSE_CODE_STORE_NOT_FOUND = '601';

    /**
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_STORE_NOT_FOUND = 'store.validation.store_not_found';
}
