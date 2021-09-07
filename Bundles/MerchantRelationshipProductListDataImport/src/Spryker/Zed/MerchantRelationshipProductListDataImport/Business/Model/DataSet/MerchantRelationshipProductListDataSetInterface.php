<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\DataSet;

interface MerchantRelationshipProductListDataSetInterface
{
    /**
     * @var string
     */
    public const MERCHANT_RELATION_KEY = 'merchant_relation_key';
    /**
     * @var string
     */
    public const PRODUCT_LIST_KEY = 'product_list_key';

    /**
     * @var string
     */
    public const ID_MERCHANT_RELATIONSHIP = 'id_merchant_relationship';
    /**
     * @var string
     */
    public const ID_PRODUCT_LIST = 'id_product_list';
}
