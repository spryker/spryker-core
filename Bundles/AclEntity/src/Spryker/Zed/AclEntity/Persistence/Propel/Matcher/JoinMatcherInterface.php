<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Matcher;

use Propel\Runtime\ActiveQuery\Join;

interface JoinMatcherInterface
{
    /**
     * @param string $tableName
     * @param array<\Propel\Runtime\ActiveQuery\Join> $joins
     *
     * @return array<\Propel\Runtime\ActiveQuery\Join>
     */
    public function matchByRightTableName(string $tableName, array $joins): array;

    /**
     * @param string $tableName
     * @param array<\Propel\Runtime\ActiveQuery\Join> $joins
     *
     * @return \Propel\Runtime\ActiveQuery\Join|null
     */
    public function matchOneByRightTableName(string $tableName, array $joins): ?Join;

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $searchJoin
     * @param array<\Propel\Runtime\ActiveQuery\Join> $joins
     *
     * @return \Propel\Runtime\ActiveQuery\Join|null
     */
    public function matchByCompleteEquality(Join $searchJoin, array $joins): ?Join;
}
