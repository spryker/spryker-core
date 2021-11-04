<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport\Business\Model\DataSet;

interface CompanyRoleDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_COMPANY_ROLE_KEY = 'company_role_key';

    /**
     * @var string
     */
    public const COLUMN_COMPANY_ROLE_NAME = 'company_role_name';

    /**
     * @var string
     */
    public const COLUMN_COMPANY_KEY = 'company_key';

    /**
     * @var string
     */
    public const COLUMN_IS_DEFAULT = 'is_default';
}
