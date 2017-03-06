<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence;

use Exception;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\CriteriaMapper;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapper;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\OperatorBuilder as QueryBuilderOperatorBuilder;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\QueryBuilder;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\TransferMapper\RuleTransferMapper;
use Spryker\Zed\QueryPropelRule\QueryPropelRuleDependencyProvider;

/**
 * @method \Spryker\Zed\QueryPropelRule\QueryPropelRuleConfig getConfig()
 * @method \Spryker\Zed\QueryPropelRule\Persistence\QueryPropelRuleQueryContainer getQueryContainer()
 */
class QueryPropelRulePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\TransferMapper\RuleTransferMapperInterface
     */
    public function createRuleTransferMapper()
    {
        return new RuleTransferMapper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\QueryBuilderInterface
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder(
            $this->createQueryBuilderCriteriaMapper()
        );
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\CriteriaMapperInterface
     */
    protected function createQueryBuilderCriteriaMapper()
    {
        return new CriteriaMapper(
            $this->createQueryBuilderOperatorBuilder(),
            $this->createAttributeCriterionMapper()
        );
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\OperatorBuilder
     */
    protected function createQueryBuilderOperatorBuilder()
    {
        return new QueryBuilderOperatorBuilder();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapperInterface
     */
    protected function createAttributeCriterionMapper()
    {
        return new JsonCriterionMapper(
            $this->createJsonMapper()
        );
    }

    /**
     * @throws \Exception
     *
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\JsonMapperInterface
     */
    protected function createJsonMapper()
    {
        $jsonMapperClassName = $this->getConfig()->getJsonMapperClassName();
        if (!$jsonMapperClassName) {
            throw new Exception('Unsupported JsonMapper type for current database engine');
        }

        return new $jsonMapperClassName();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Dependency\Service\QueryPropelRuleToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(QueryPropelRuleDependencyProvider::SERVICE_UTIL_ENCODING);
    }

}
