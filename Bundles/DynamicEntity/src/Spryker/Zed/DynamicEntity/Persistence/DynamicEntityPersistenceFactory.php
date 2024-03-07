<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence;

use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Propel\Runtime\Map\DatabaseMap;
use Propel\Runtime\Propel;
use Spryker\Zed\DynamicEntity\Dependency\Service\DynamicEntityToUtilEncodingServiceInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityDependencyProvider;
use Spryker\Zed\DynamicEntity\Persistence\Builder\DynamicEntityQueryBuilder;
use Spryker\Zed\DynamicEntity\Persistence\Builder\DynamicEntityQueryBuilderInterface;
use Spryker\Zed\DynamicEntity\Persistence\Filter\DynamicEntityFieldCreationFilter;
use Spryker\Zed\DynamicEntity\Persistence\Filter\DynamicEntityFieldUpdateFilter;
use Spryker\Zed\DynamicEntity\Persistence\Filter\DynamicEntityFilterInterface;
use Spryker\Zed\DynamicEntity\Persistence\Filter\Strategy\DefaultFilterStrategy;
use Spryker\Zed\DynamicEntity\Persistence\Filter\Strategy\FilterStrategyInterface;
use Spryker\Zed\DynamicEntity\Persistence\Filter\Strategy\InFilterStrategy;
use Spryker\Zed\DynamicEntity\Persistence\Filter\Validator\DynamicEntityFieldCreationPreValidator;
use Spryker\Zed\DynamicEntity\Persistence\Filter\Validator\DynamicEntityFieldUpdatePreValidator;
use Spryker\Zed\DynamicEntity\Persistence\Filter\Validator\DynamicEntityPreValidatorInterface;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\ExceptionToErrorMapper;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\ExceptionToErrorMapperInterface;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\Mysql\DuplicateEntryExceptionToErrorMapper;
use Spryker\Zed\DynamicEntity\Persistence\Mapper\Postgresql\DuplicateKeyExceptionToErrorMapper;
use Spryker\Zed\DynamicEntity\Persistence\Propel\Mapper\DynamicEntityMapper;
use Spryker\Zed\DynamicEntity\Persistence\Resetter\DynamicEntityResetter;
use Spryker\Zed\DynamicEntity\Persistence\Resetter\DynamicEntityResetterInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface getRepository()
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DynamicEntity\DynamicEntityConfig getConfig()
 */
class DynamicEntityPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    public function createDynamicEntityConfigurationQuery(): SpyDynamicEntityConfigurationQuery
    {
        return SpyDynamicEntityConfigurationQuery::create();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Propel\Mapper\DynamicEntityMapper
     */
    public function createDynamicEntityMapper(): DynamicEntityMapper
    {
        return new DynamicEntityMapper($this->getServiceUtilEncoding());
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Builder\DynamicEntityQueryBuilderInterface
     */
    public function createDynamicEntityQueryBuilder(): DynamicEntityQueryBuilderInterface
    {
        return new DynamicEntityQueryBuilder(
            $this->getPropelDatabaseMap(),
            $this->getFilterStrategies(),
        );
    }

    /**
     * @return \Propel\Runtime\Map\DatabaseMap
     */
    public function getPropelDatabaseMap(): DatabaseMap
    {
        return Propel::getDatabaseMap();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Filter\DynamicEntityFilterInterface
     */
    public function createDynamicEntityFieldCreationFilter(): DynamicEntityFilterInterface
    {
        return new DynamicEntityFieldCreationFilter();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Filter\DynamicEntityFilterInterface
     */
    public function createDynamicEntityFieldUpdateFilter(): DynamicEntityFilterInterface
    {
        return new DynamicEntityFieldUpdateFilter();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Filter\Validator\DynamicEntityPreValidatorInterface
     */
    public function createDynamicEntityFieldCreationPreValidator(): DynamicEntityPreValidatorInterface
    {
        return new DynamicEntityFieldCreationPreValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Filter\Validator\DynamicEntityPreValidatorInterface
     */
    public function createDynamicEntityFieldUpdatePreValidator(): DynamicEntityPreValidatorInterface
    {
        return new DynamicEntityFieldUpdatePreValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Mapper\ExceptionToErrorMapperInterface
     */
    public function createExceptionToErrorMapper(): ExceptionToErrorMapperInterface
    {
        return new ExceptionToErrorMapper(
            $this->getDatabaseExceptionToErrorMappers(),
        );
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface>
     */
    public function getDatabaseExceptionToErrorMappers(): array
    {
        return [
            $this->createDuplicateEntryExceptionToErrorMapper(),
            $this->createDuplicateKeyExceptionToErrorMapper(),
        ];
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface
     */
    public function createDuplicateEntryExceptionToErrorMapper(): DatabaseExceptionToErrorMapperInterface
    {
        return new DuplicateEntryExceptionToErrorMapper();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Mapper\DatabaseExceptionToErrorMapperInterface
     */
    public function createDuplicateKeyExceptionToErrorMapper(): DatabaseExceptionToErrorMapperInterface
    {
        return new DuplicateKeyExceptionToErrorMapper();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Resetter\DynamicEntityResetterInterface
     */
    public function createDynamicEntityResetter(): DynamicEntityResetterInterface
    {
        return new DynamicEntityResetter();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Filter\Strategy\FilterStrategyInterface
     */
    public function createDefaultFilterStrategy(): FilterStrategyInterface
    {
        return new DefaultFilterStrategy();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Persistence\Filter\Strategy\FilterStrategyInterface
     */
    public function createInFilterStrategy(): FilterStrategyInterface
    {
        return new InFilterStrategy($this->getServiceUtilEncoding());
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Persistence\Filter\Strategy\FilterStrategyInterface>
     */
    public function getFilterStrategies(): array
    {
        return [
            $this->createInFilterStrategy(),
            $this->createDefaultFilterStrategy(),
        ];
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Dependency\Service\DynamicEntityToUtilEncodingServiceInterface
     */
    public function getServiceUtilEncoding(): DynamicEntityToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(DynamicEntityDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
