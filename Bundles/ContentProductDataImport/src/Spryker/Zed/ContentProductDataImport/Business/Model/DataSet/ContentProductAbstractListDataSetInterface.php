<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\DataSet;

interface ContentProductAbstractListDataSetInterface
{
    /**
     * @var string
     */
    public const CONTENT_PRODUCT_ABSTRACT_LIST_KEY = 'key';

    /**
     * @var string
     */
    public const CONTENT_LOCALIZED_PRODUCT_ABSTRACT_LIST_TERMS = 'content_localized_product_abstract_list_terms';

    /**
     * @var string
     */
    public const COLUMN_NAME = 'name';

    /**
     * @var string
     */
    public const COLUMN_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const COLUMN_SKUS = 'skus';

    /**
     * @var string
     */
    public const COLUMN_IDS = 'ids';

    /**
     * @var string
     */
    public const COLUMN_LOCALES = 'locales';

    /**
     * @var string
     */
    public const COLUMN_DEFAULT_SKUS = 'skus.default';
}
