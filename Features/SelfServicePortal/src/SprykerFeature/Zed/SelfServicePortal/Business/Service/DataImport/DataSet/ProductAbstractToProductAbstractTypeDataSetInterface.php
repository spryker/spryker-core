<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet;

interface ProductAbstractToProductAbstractTypeDataSetInterface
{
    /**
     * @var string
     */
    public const ABSTRACT_SKU = 'abstract_sku';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_TYPE_KEY = 'product_abstract_type_key';

    /**
     * @var string
     */
    public const ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    public const ID_PRODUCT_ABSTRACT_TYPE = 'id_product_abstract_type';
}
