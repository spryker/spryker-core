<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet;

interface ProductToProductClassDataSetInterface
{
    /**
     * @var string
     */
    public const SKU = 'sku';

    /**
     * @var string
     */
    public const PRODUCT_CLASS_KEY = 'product_class_key';

    /**
     * @var string
     */
    public const ID_PRODUCT = 'id_product';

    /**
     * @var string
     */
    public const ID_PRODUCT_CLASS = 'id_product_class';
}
