<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceDataFeed;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Price\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Spryker\Zed\DataFeed\Persistence\QueryBuilder\CategoryQueryBuilder;
use Spryker\Zed\DataFeed\Persistence\QueryBuilder\PriceQueryBuilder;
use Spryker\Zed\DataFeed\Persistence\QueryBuilder\ProductQueryBuilder;
use Spryker\Zed\DataFeed\Persistence\QueryBuilder\StockQueryBuilder;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceDataFeedConfig extends AbstractBundleConfig
{
}
