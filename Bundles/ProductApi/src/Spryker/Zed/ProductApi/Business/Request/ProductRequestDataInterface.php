<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Request;

interface ProductRequestDataInterface
{
    /**
     * @var string
     */
    public const KEY_ID = 'id';

    /**
     * @var string
     */
    public const KEY_NAME = 'name';

    /**
     * @var string
     */
    public const KEY_SKU = 'sku';

    /**
     * @var string
     */
    public const KEY_FK_LOCALE = 'fk_locale';

    /**
     * @var string
     */
    public const KEY_ID_TAX_SET = 'id_tax_set';
}
