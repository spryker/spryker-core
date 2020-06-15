<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence;

use Exception;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\ColumnQueryMapper;
use Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\PaginationQueryMapper;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\CriteriaMapper;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapper;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\OperatorBuilder as QueryBuilderOperatorBuilder;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\QueryBuilder;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\TransferMapper\RuleTransferMapper;
use Spryker\Zed\PropelQueryBuilder\PropelQueryBuilderDependencyProvider;

/**
 * @method \Spryker\Zed\PropelQueryBuilder\PropelQueryBuilderConfig getConfig()
 * @method \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainerInterface getQueryContainer()
 */
class PropelQueryBuilderPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\TransferMapper\RuleTransferMapperInterface
     */
    public function createRuleTransferMapper()
    {
        return new RuleTransferMapper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\QueryBuilderInterface
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder(
            $this->createQueryBuilderCriteriaMapper(),
            $this->createColumnQueryMapper(),
            $this->createPaginationQueryMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\ColumnQueryMapperInterface
     */
    protected function createColumnQueryMapper()
    {
        return new ColumnQueryMapper();
    }

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\PaginationQueryMapperInterface
     */
    protected function createPaginationQueryMapper()
    {
        return new PaginationQueryMapper();
    }

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\CriteriaMapperInterface
     */
    protected function createQueryBuilderCriteriaMapper()
    {
        return new CriteriaMapper(
            $this->createQueryBuilderOperatorBuilder(),
            $this->createAttributeCriterionMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\OperatorBuilder
     */
    protected function createQueryBuilderOperatorBuilder()
    {
        return new QueryBuilderOperatorBuilder();
    }

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapperInterface
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
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonMapperInterface
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
     * @return \Spryker\Zed\PropelQueryBuilder\Dependency\Service\PropelQueryBuilderToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(PropelQueryBuilderDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
