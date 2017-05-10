<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\FactFinder;

interface FactFinderConstants
{

    const ENVIRONMENT = 'FACT_FINDER:ENVIRONMENT';
    const ENVIRONMENT_PRODUCTION = 'FACT_FINDER:ENVIRONMENT_PRODUCTION';
    const ENVIRONMENT_DEVELOPMENT = 'FACT_FINDER:ENVIRONMENT_DEVELOPMENT';
    const ENVIRONMENT_TEST = 'FACT_FINDER:ENVIRONMENT_TEST';
    const CSV_DIRECTORY = 'FACT_FINDER:CSV_DIRECTORY';
    const EXPORT_QUERY_LIMIT = 'FACT_FINDER:EXPORT_QUERY_LIMIT';
    const EXPORT_FILE_NAME_PREFIX = 'FACT_FINDER:EXPORT_FILE_NAME_PREFIX';
    const EXPORT_FILE_NAME_DELIMITER = 'FACT_FINDER:EXPORT_FILE_NAME_DELIMITER';
    const EXPORT_FILE_EXTENSION = 'FACT_FINDER:EXPORT_FILE_EXTENSION';
    const PHP_LOGGER_CONFIG_PATH = 'FACT_FINDER:PHP_LOGGER_CONFIG_PATH';

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
    const ITEM_CATEGORY_ID = 'CategoryId';
    const ITEM_PARENT_CATEGORY_NAME = 'ParentCategoryName';
    const ITEM_PARENT_CATEGORY_NODE_ID = 'ParentCategoryNodeId';

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
