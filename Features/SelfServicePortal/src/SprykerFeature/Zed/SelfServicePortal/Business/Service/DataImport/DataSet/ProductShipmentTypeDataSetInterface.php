<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet;

interface ProductShipmentTypeDataSetInterface
{
    /**
     * @var string
     */
    public const CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const SHIPMENT_TYPE_KEY = 'shipment_type_key';

    /**
     * @var string
     */
    public const ID_PRODUCT = 'id_product';

    /**
     * @var string
     */
    public const ID_SHIPMENT_TYPE = 'id_shipment_type';
}
