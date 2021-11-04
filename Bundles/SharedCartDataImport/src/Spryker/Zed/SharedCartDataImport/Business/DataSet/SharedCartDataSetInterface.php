<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SharedCartDataImport\Business\DataSet;

interface SharedCartDataSetInterface
{
    /**
     * @var string
     */
    public const KEY_QUOTE = 'quote_key';

    /**
     * @var string
     */
    public const KEY_COMPANY_USER = 'company_user_key';

    /**
     * @var string
     */
    public const PERMISSION_GROUP_NAME = 'permission_group_name';

    /**
     * @var string
     */
    public const ID_QUOTE = 'id_quote';

    /**
     * @var string
     */
    public const ID_COMPANY_USER = 'id_company_user';

    /**
     * @var string
     */
    public const ID_PERMISSION_GROUP = 'id_quote_permission_group';
}
