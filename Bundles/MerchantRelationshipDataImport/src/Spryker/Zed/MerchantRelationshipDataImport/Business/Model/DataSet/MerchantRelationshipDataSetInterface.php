<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipDataImport\Business\Model\DataSet;

interface MerchantRelationshipDataSetInterface
{
    /**
     * @var string
     */
    public const MERCHANT_RELATIONSHIP_KEY = 'merchant_relation_key';
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';
    /**
     * @var string
     */
    public const COMPANY_BUSINESS_UNIT_OWNER_KEY = 'company_business_unit_owner_key';
    /**
     * @var string
     */
    public const COMPANY_BUSINESS_UNIT_ASSIGNEE_KEYS = 'company_business_unit_assignee_keys';

    /**
     * @var string
     */
    public const ID_MERCHANT = 'id_merchant';
    /**
     * @var string
     */
    public const ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';
    /**
     * @var string
     */
    public const ID_COMPANY_BUSINESS_UNIT_ASSIGNEE_COLLECTION = 'id_company_business_unit_assignee_collection';
}
