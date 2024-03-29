<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet;

interface BusinessOnBehalfCompanyUserDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_BUSINESS_UNIT_KEY = 'business_unit_key';

    /**
     * @var string
     */
    public const COLUMN_COMPANY_KEY = 'company_key';

    /**
     * @var string
     */
    public const COLUMN_CUSTOMER_REFERENCE = 'customer_reference';

    /**
     * @var string
     */
    public const COLUMN_ID_BUSINESS_UNIT = 'idBusinessUnit';

    /**
     * @var string
     */
    public const COLUMN_ID_COMPANY = 'idCompany';

    /**
     * @var string
     */
    public const COLUMN_ID_CUSTOMER = 'idCustomer';

    /**
     * @var string
     */
    public const COLUMN_DEFAULT = 'default';
}
