<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrmExtension\Dependency\Plugin;

/**
 * Gets classes list to be declared in target extended class.
 */
interface DeclareClassesToBeUsedInterface
{
    /**
     * Specification:
     * - Declares classes to be used in a generated Propel objects.
     * - Classes will be inserted to the `use` section of a generated Propel class.
     *
     * @api
     *
     * @return array<string>
     */
    public function getClassesToDeclare(): array;
}
