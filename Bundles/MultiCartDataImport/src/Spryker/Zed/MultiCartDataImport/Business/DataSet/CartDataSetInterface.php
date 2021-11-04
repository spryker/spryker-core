<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MultiCartDataImport\Business\DataSet;

interface CartDataSetInterface
{
    /**
     * @var string
     */
    public const KEY_CART = 'key';

    /**
     * @var string
     */
    public const KEY_STORE = 'store';

    /**
     * @var string
     */
    public const CUSTOMER_REFERENCE = 'customer_reference';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';
}
