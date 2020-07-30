<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel;

use Codeception\Actor;
use Codeception\Scenario;
use DateTime;
use Faker\Factory;
use PHPUnit\Framework\ExpectationFailedException;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Map\ColumnMap;
use Symfony\Component\Finder\Finder;
use Throwable;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class PropelPersistenceTester extends Actor
{
    use _generated\PropelPersistenceTesterActions;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var array
     */
    protected $queryClasses;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->faker = Factory::create();
    }

    /**
     * @param string $entityClassName
     * @param int $numberOfEntities
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    public function getEntityCollectionForInsert(string $entityClassName, int $numberOfEntities = 2): array
    {
        $entities = [];
        try {
            $tableMapClassName = $entityClassName::TABLE_MAP;
            $tableMapClass = new $tableMapClassName();

            if (count($tableMapClass->getPrimaryKeys()) > 1) {
                codecept_debug(sprintf('Could not use %s for testing. Looks like it makes use of a composite key.', $entityClassName));

                return $entities;
            }
            for ($i = 0; $i <= $numberOfEntities - 1; $i++) {
                $entity = $this->fillEntityWithRequiredFields(new $entityClassName());

                $entities[] = $entity;
            }

            return $entities;
        } catch (Throwable $throwable) {
            codecept_debug(sprintf('Could not create entities for %s because of: %s', $entityClassName, $throwable->getMessage()));

            return $entities;
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    protected function fillEntityWithRequiredFields(ActiveRecordInterface $entity): ActiveRecordInterface
    {
        $entityClassName = get_class($entity);
        $tableMapClassName = $entityClassName::TABLE_MAP;
        $tableMapClass = new $tableMapClassName();
        $columnMapCollection = $tableMapClass->getColumns();

        foreach ($columnMapCollection as $columnMap) {
            if ($columnMap->isPrimaryKey() && $tableMapClass->getPrimaryKeyMethodInfo() !== null) {
                continue;
            }

            if ($columnMap->isNotNull()) {
                $setter = sprintf('set%s', $columnMap->getPhpName());
                $entity->$setter($this->getValue($columnMap));
            }
        }

        return $entity;
    }

    /**
     * @param \Propel\Runtime\Map\ColumnMap $columnMap
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return bool|\DateTime|float|int|mixed|string|false
     */
    public function getValue(ColumnMap $columnMap)
    {
        if ($columnMap->getRelatedTableName() !== '') { // foreign_key column
            $relatedQueryClass = $this->getQueryClassByRelatedTableName($columnMap->getRelatedTableName());
            $relatedEntityCollection = $relatedQueryClass::create()->find();
            $relatedEntity = $relatedEntityCollection->getFirst();

            if (!$relatedEntity) {
                $relatedEntity = $relatedQueryClass::create()->findOneOrCreate();
                $relatedEntity = $this->fillEntityWithRequiredFields($relatedEntity);
                $relatedEntity->save();
            }
            $filter = new UnderscoreToCamelCase();
            $getterMethod = sprintf('get%s', ucfirst($filter->filter($columnMap->getRelatedColumnName())));

            return $relatedEntity->$getterMethod();
        }

        if ($columnMap->getType() === 'VARCHAR') {
            $maxSize = $columnMap->getSize() ?: 255;

            return substr($this->faker->md5, 0, $maxSize);
        }

        if ($columnMap->getType() === 'LONGVARCHAR' || $columnMap->getType() === 'CLOB') {
            return $this->faker->text;
        }

        if ($columnMap->getType() === 'BOOLEAN') {
            return $this->faker->boolean;
        }

        if ($columnMap->getType() === 'INTEGER') {
            return $this->faker->numberBetween();
        }

        if ($columnMap->getType() === 'BIGINT') {
            return $this->faker->numberBetween();
        }

        if ($columnMap->getType() === 'ENUM') {
            return current($columnMap->getValueSet());
        }

        if ($columnMap->getType() === 'DECIMAL' || $columnMap->getType() === 'FLOAT') {
            return 0.01;
        }

        if ($columnMap->getType() === 'TIMESTAMP' || $columnMap->getType() === 'DATE') {
            return new DateTime();
        }

        throw new ExpectationFailedException(sprintf(
            'Could not create a value for "%s.%s" type "%s".',
            $columnMap->getTableName(),
            $columnMap->getName(),
            $columnMap->getType()
        ));
    }

    /**
     * @param string $relatedTableName
     *
     * @return object
     */
    protected function getQueryClassByRelatedTableName(string $relatedTableName)
    {
        if ($this->queryClasses === null) {
            $finder = new Finder();
            $finder->in(sprintf('%s/Orm/Zed/*/Persistence/', rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR)))
                ->name('*Query.php')
                ->depth('== 0');

            $filter = new FilterChain();
            $filter->attach(new CamelCaseToUnderscore());
            $filter->attach(new StringToLower());

            foreach ($finder as $splFileInfo) {
                $className = '\\' . str_replace([APPLICATION_SOURCE_DIR, '.php', '/'], ['', '', '\\'], $splFileInfo->getPathname());
                $classNameParts = explode('\\', str_replace('Query', '', $className));
                $tableName = $filter->filter(array_pop($classNameParts));
                $this->queryClasses[$tableName] = $className;
            }
        }

        return new $this->queryClasses[$relatedTableName]();
    }
}
