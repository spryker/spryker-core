<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Propel\Persistence\BatchProcessor;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

trait CascadeActiveRecordBatchProcessorTrait
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @var array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected static array $entityList = [];

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function sharedPersist(ActiveRecordInterface $entity): void
    {
        static::$entityList[] = $entity;
    }

    /**
     * @return bool
     */
    public function recursiveCommit(): bool
    {
        if (!static::$entityList) {
            return true;
        }

        $toCommit = static::$entityList;
        static::$entityList = [];
        foreach ($toCommit as $entity) {
            $this->persist($entity);
        }
        $result = $this->commit();

        if (static::$entityList) {
            $this->recursiveCommit();
        }

        return $result;
    }
}
