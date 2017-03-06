<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule;

use Spryker\Shared\QueryPropelRule\QueryPropelRuleConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\MySql\JsonMapper as MySqlJsonMapper;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\PostgreSql\JsonMapper as PostgreSqlJsonMapper;

class QueryPropelRuleConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getDbEngineName()
    {
        return $this->getConfig()->get(QueryPropelRuleConstants::ZED_DB_ENGINE);
    }

    /**
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
     * @return null|string
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
