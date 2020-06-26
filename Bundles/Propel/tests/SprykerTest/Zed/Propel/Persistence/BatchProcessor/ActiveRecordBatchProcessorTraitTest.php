<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Persistence\BatchProcessor;

use Closure;
use Codeception\Test\Unit;
use PHPUnit\Framework\ExpectationFailedException;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Throwable;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Persistence
 * @group BatchProcessor
 * @group ActiveRecordBatchProcessorTraitTest
 * Add your own group annotations below this line
 */
class ActiveRecordBatchProcessorTraitTest extends Unit
{
    protected const MODULES_TO_EXCLUDE = [
        'Payone',
    ];

    /**
     * @var \SprykerTest\Zed\Propel\PropelPersistenceTester
     */
    public $tester;

    /**
     * @var array
     */
    protected $queryClasses;

    /**
     * @return array[]
     */
    public function dataProvider(): array
    {
        $finder = new Finder();
        $finder->in(sprintf('%s/Orm/Zed/*/Persistence/', rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR)))
            ->filter($this->getDirectoriesFilter())
            ->depth('== 0');

        $classNames = [];

        foreach ($finder as $splFileInfo) {
            if ($this->exclude($splFileInfo)) {
                continue;
            }

            $className = str_replace([APPLICATION_SOURCE_DIR, '.php', '/'], ['', '', '\\'], $splFileInfo->getPathname());
            $classNames[] = [$className];
        }

        return $classNames;
    }

    /**
     * @return \Closure
     */
    protected function getDirectoriesFilter(): Closure
    {
        return function (SplFileInfo $splFileInfo) {
            foreach (static::MODULES_TO_EXCLUDE as $module) {
                if (strpos($splFileInfo->getPath(), $module)) {
                    return false;
                }
            }
        };
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return bool
     */
    protected function exclude(SplFileInfo $splFileInfo): bool
    {
        if ($splFileInfo->isDir()) {
            return true;
        }

        $excludedFileNames = [
            'Query.php',
            'Archive.php',
            'History.php',
        ];

        foreach ($excludedFileNames as $excludedFileName) {
            if (strpos($splFileInfo->getPathname(), $excludedFileName) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @dataProvider dataProvider()
     *
     * @group insert
     *
     * @param string $entityClassName
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testCommitShouldInsertEntitiesInBatch(string $entityClassName)
    {
        codecept_debug($entityClassName);

        try {
            $batchProcessor = $this->getActiveRecordBatchProcessor();

            foreach ($this->tester->getEntityCollectionForInsert($entityClassName) as $entity) {
                $batchProcessor->persist($entity);
            }

            $this->assertTrue($batchProcessor->commit());
        } catch (Throwable $throwable) {
            $message = $throwable->getMessage();
            if (strpos($message, 'SQLSTATE[23505]: Unique violation') !== false || strpos($message, 'Cannot assign bundle product or use bundled product as a bundle') !== false) {
                codecept_debug($throwable->getMessage());

                return;
            }

            throw new ExpectationFailedException($throwable->getMessage(), null, $throwable);
        }
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait
     */
    protected function getActiveRecordBatchProcessor()
    {
        return $this->getMockForTrait(ActiveRecordBatchProcessorTrait::class);
    }

    /**
     * @dataProvider dataProvider()
     *
     * @group updateSmth
     *
     * @param string $entityClassName
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testCommitShouldUpdateEntitiesInBatch(string $entityClassName)
    {
        $tableMapClassName = $entityClassName::TABLE_MAP;
        $tableMapClass = new $tableMapClassName();
        $columnMapCollection = $tableMapClass->getColumns();

        $columnNamesForUpdate = [];
        $hasPrimaryKey = false;

        foreach ($columnMapCollection as $columnMap) {
            if ($columnMap->isPrimaryKey()) {
                $hasPrimaryKey = true;

                continue;
            }

            $columnNamesForUpdate[] = $columnMap->getName();
        }

        if ($hasPrimaryKey === false) {
            codecept_debug(sprintf('Can not use %s for update test as it does not have a primary key.', $entityClassName));

            return;
        }

        if (count($columnNamesForUpdate) === 0) {
            codecept_debug(sprintf('Can not use %s for update test as it has only primary keys.', $entityClassName));

            return;
        }

        $queryClass = sprintf('%sQuery', $entityClassName);
        $entityCollection = $queryClass::create()
            ->limit(2)
            ->find();

        $batchProcessor = $this->getActiveRecordBatchProcessor();

        foreach ($entityCollection as $entity) {
            $entity = $this->addModifiedColumn($columnMapCollection, $entity);
            $batchProcessor->persist($entity);
        }

        try {
            $this->assertTrue($batchProcessor->commit());
        } catch (Throwable $throwable) {
            $message = $throwable->getMessage();
            if (strpos($message, 'Cannot assign bundle product or use bundled product as a bundle') !== false) {
                codecept_debug($throwable->getMessage());

                return;
            }

            throw new ExpectationFailedException($throwable->getMessage(), null, $throwable);
        }
    }

    /**
     * @param \Propel\Runtime\Map\ColumnMap[] $columnMapCollection
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    protected function addModifiedColumn(array $columnMapCollection, ActiveRecordInterface $entity): ActiveRecordInterface
    {
        foreach ($columnMapCollection as $columnMap) {
            if ($columnMap->isPrimaryKey() || $columnMap->getRelatedTableName() !== '') {
                continue;
            }

            $value = $this->tester->getValue($columnMap);
            $setterMethod = sprintf('set%s', $columnMap->getPhpName());
            $getterMethod = sprintf('get%s', $columnMap->getPhpName());

            $previousValue = $entity->{$getterMethod}();

            $entity->{$setterMethod}($value);
            $entity->{$setterMethod}($previousValue);

            return $entity;
        }

        return $entity;
    }
}
