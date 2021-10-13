<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\Model\DataSet;

interface MerchantDataSetInterface
{
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';
    /**
     * @var string
     */
    public const NAME = 'merchant_name';
    /**
     * @var string
     */
    public const REGISTRATION_NUMBER = 'registration_number';
    /**
     * @var string
     */
    public const STATUS = 'status';
    /**
     * @var string
     */
    public const EMAIL = 'email';
    /**
     * @var string
     */
    public const IS_ACTIVE = 'is_active';
    /**
     * @var string
     */
    public const URL = 'url';
}
