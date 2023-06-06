<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataSet;

interface ProductOfferShipmentTypeDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_SHIPMENT_TYPE_KEY = 'shipment_type_key';

    /**
     * @var string
     */
    public const COLUMN_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    public const UUID_SHIPMENT_TYPE = 'uuid_shipment_type';
}
