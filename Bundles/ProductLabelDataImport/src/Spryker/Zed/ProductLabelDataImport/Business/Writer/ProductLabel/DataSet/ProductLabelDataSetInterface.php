<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet;

interface ProductLabelDataSetInterface
{
    /**
     * @var string
     */
    public const COL_NAME = 'name';
    /**
     * @var string
     */
    public const COL_IS_ACTIVE = 'is_active';
    /**
     * @var string
     */
    public const COL_IS_DYNAMIC = 'is_dynamic';
    /**
     * @var string
     */
    public const COL_IS_EXCLUSIVE = 'is_exclusive';
    /**
     * @var string
     */
    public const COL_FRONT_END_REFERENCE = 'front_end_reference';
    /**
     * @var string
     */
    public const COL_VALID_FROM = 'valid_from';
    /**
     * @var string
     */
    public const COL_VALID_TO = 'valid_to';
    /**
     * @var string
     */
    public const COL_PRODUCT_ABSTRACT_SKUS = 'product_abstract_skus';
    /**
     * @var string
     */
    public const COL_PRIORITY = 'priority';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_LABEL = 'id_product_label';
    /**
     * @var string
     */
    public const COL_PRODUCT_ABSTRACT_IDS = 'product_abstract_ids';
}
