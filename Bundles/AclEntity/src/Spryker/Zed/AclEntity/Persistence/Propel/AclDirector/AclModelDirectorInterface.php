<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

interface AclModelDirectorInterface
{
    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    public function inspectCreate(ActiveRecordInterface $entity): void;

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    public function inspectUpdate(ActiveRecordInterface $entity): void;

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    public function inspectDelete(ActiveRecordInterface $entity): void;
}
