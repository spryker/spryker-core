<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\FactFinder;

interface FactFinderConstants
{

    const PROVIDER_NAME = 'FactFinder';

    const ENV = 'FF env';
    const ENV_PRODUCTION = 'production';
    const ENV_DEVELOPMENT = 'development';
    const ENV_TEST = 'test';
    const CSV_DIRECTORY = 'factfinder csv directory';

    const CONFIG_BASIC_AUTH_USERNAME = 'fact_finder_basic_auth_username';
    const CONFIG_BASIC_AUTH_PASSWORD = 'fact_finder_basic_auth_password';

    const ITEM_PRODUCT_NUMBER = 'ProductNumber';
    const ITEM_NAME = 'Name';
    const ABSTRACT_URL = 'AbstractUrl';
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
        self::ABSTRACT_URL,
        self::ITEM_PRICE,
        self::ITEM_STOCK,
        self::ITEM_CATEGORY,
        self::ITEM_CATEGORY_PATH,
        self::ITEM_PRODUCT_URL,
        self::ITEM_IMAGE_URL,
        self::ITEM_DESCRIPTION,
    ];

}
