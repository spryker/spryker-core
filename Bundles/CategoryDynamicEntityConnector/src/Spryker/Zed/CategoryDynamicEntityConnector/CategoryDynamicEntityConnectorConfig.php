<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CategoryDynamicEntityConnectorConfig extends AbstractBundleConfig
{
    /**
     * @uses \Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap::TABLE_NAME
     *
     * @var string
     */
    protected const TABLE_NAME_CATEGORY = 'spy_category';

    /**
     * @uses \Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap::TABLE_NAME
     *
     * @var string
     */
    protected const TABLE_NAME_CATEGORY_NODE = 'spy_category_node';

    /**
     * @uses \Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap::TABLE_NAME
     *
     * @var string
     */
    protected const TABLE_NAME_CATEGORY_ATTRIBUTE = 'spy_category_attribute';

    /**
     * Specification:
     * - Returns a list of tables for which creating category URLs should be triggered.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCategoryUrlOnCreateByDynamicEntityApplicableTables(): array
    {
        return [
            static::TABLE_NAME_CATEGORY_NODE,
            static::TABLE_NAME_CATEGORY_ATTRIBUTE,
        ];
    }

    /**
     * Specification:
     * - Returns a list of tables for which creating category URLs should be triggered.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCategoryClosureTableOnCreateByDynamicEntityApplicableTables(): array
    {
        return [
            static::TABLE_NAME_CATEGORY_NODE,
        ];
    }

    /**
     * Specification:
     * - Returns a list of tables for which creating category URLs should be triggered.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCategoryTreePublishOnCreateByDynamicEntityApplicableTables(): array
    {
        return [
            static::TABLE_NAME_CATEGORY_NODE,
            static::TABLE_NAME_CATEGORY_ATTRIBUTE,
        ];
    }

    /**
     * Specification:
     * - Returns a list of tables for which creating category URLs should be triggered.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCategoryUrlOnUpdateByDynamicEntityApplicableTables(): array
    {
        return [
            static::TABLE_NAME_CATEGORY_NODE,
            static::TABLE_NAME_CATEGORY_ATTRIBUTE,
        ];
    }

    /**
     * Specification:
     * - Returns a list of tables for which creating category URLs should be triggered.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCategoryClosureTableOnUpdateByDynamicEntityApplicableTables(): array
    {
        return [
            static::TABLE_NAME_CATEGORY_NODE,
        ];
    }

    /**
     * Specification:
     * - Returns a list of tables for which creating category URLs should be triggered.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCategoryTreePublishOnUpdateByDynamicEntityApplicableTables(): array
    {
        return [
            static::TABLE_NAME_CATEGORY,
            static::TABLE_NAME_CATEGORY_NODE,
            static::TABLE_NAME_CATEGORY_ATTRIBUTE,
        ];
    }
}
