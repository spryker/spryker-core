<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferServicePointDataImport\Business\DataSet;

interface ProductOfferServiceDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_SERVICE_KEY = 'service_key';

    /**
     * @var string
     */
    public const COLUMN_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    public const COLUMN_ID_PRODUCT_OFFER = 'id_product_offer';

    /**
     * @var string
     */
    public const COLUMN_ID_SERVICE = 'id_service';
}
