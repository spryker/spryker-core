<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api;

class ApiConstants
{

    const REQUEST_FORMAT = 'json';

    const TRANSACTION_TYPE_SEARCH = 'search';
    const TRANSACTION_TYPE_RECOMMENDATION = 'recommendation';
    const TRANSACTION_TYPE_SUGGEST = 'suggest';
    const TRANSACTION_TYPE_TAG_CLOUD = 'tag_cloud';
    const TRANSACTION_TYPE_TRACKING = 'tracking';
    const TRANSACTION_TYPE_SIMILAR_RECORDS = 'similar_records';
    const TRANSACTION_TYPE_PRODUCT_CAMPAIGN = 'product_campaign';

    const ITEM_PRODUCT_NUMBER = 'ProductNumber';
    const ITEM_NAME = 'Name';
    const ITEM_PRICE = 'Price';
    const ITEM_STOCK = 'Stock';
    const ITEM_CATEGORY = 'Category';
    const ITEM_CATEGORY_PATH = 'CategoryPath';
    const ITEM_PRODUCT_URL = 'ProductURL';
    const ITEM_IMAGE_URL = 'ImageURL';
    const ITEM_DESCRIPTION = 'Description';

    const ITEM_FIELDS = [
        self::ITEM_PRODUCT_NUMBER,
        self::ITEM_NAME,
        self::ITEM_PRICE,
        self::ITEM_STOCK,
        self::ITEM_CATEGORY,
        self::ITEM_CATEGORY_PATH,
        self::ITEM_PRODUCT_URL,
        self::ITEM_IMAGE_URL,
        self::ITEM_DESCRIPTION,
    ];

}
