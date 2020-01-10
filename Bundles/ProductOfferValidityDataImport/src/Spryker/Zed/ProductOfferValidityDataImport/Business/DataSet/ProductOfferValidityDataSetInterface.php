<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidityDataImport\Business\DataSet;

interface ProductOfferValidityDataSetInterface
{
    public const FK_PRODUCT_OFFER = 'fk_product_offer';
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    public const PRODUCT_VALID_FROM = 'valid_from';
    public const PRODUCT_VALID_TO = 'valid_to';
}
