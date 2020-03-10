<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder;

use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\MySql\JsonMapper as MySqlJsonMapper;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\PostgreSql\JsonMapper as PostgreSqlJsonMapper;

class PropelQueryBuilderConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getDbEngineName()
    {
        return $this->getConfig()->get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getJsonMapperClassNameMappings()
    {
        $data = [
            'mysql' => MySqlJsonMapper::class,
            'pgsql' => PostgreSqlJsonMapper::class,
        ];

        return $data;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getJsonMapperClassName()
    {
        $type = $this->getDbEngineName();
        $classList = $this->getJsonMapperClassNameMappings();

        if (!array_key_exists($type, $classList)) {
            return null;
        }

        return $classList[$type];
    }
}
