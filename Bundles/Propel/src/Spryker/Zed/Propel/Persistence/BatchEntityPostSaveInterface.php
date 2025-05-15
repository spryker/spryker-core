<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Propel\Persistence;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

interface BatchEntityPostSaveInterface
{
    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function sharedPersist(ActiveRecordInterface $entity): void;

    /**
     * @return bool
     */
    public function recursiveCommit(): bool;

    /**
     * @return void
     */
    public function batchPostSave(): void;
}
