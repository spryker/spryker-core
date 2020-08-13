<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\DataSet;

interface ContentProductAbstractListDataSetInterface
{
    public const CONTENT_PRODUCT_ABSTRACT_LIST_KEY = 'key';
    public const CONTENT_LOCALIZED_PRODUCT_ABSTRACT_LIST_TERMS = 'content_localized_product_abstract_list_terms';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_SKUS = 'skus';
    public const COLUMN_IDS = 'ids';
    public const COLUMN_LOCALES = 'locales';
    public const COLUMN_DEFAULT_SKUS = 'skus.default';
}
