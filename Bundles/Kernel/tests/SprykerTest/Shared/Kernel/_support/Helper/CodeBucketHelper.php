<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Helper;

use Codeception\Module;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class CodeBucketHelper extends Module
{
    use DataCleanupHelperTrait;

    protected const SPRYKER_CODE_BUCKET = 'SPRYKER_CODE_BUCKET';

    /**
     * @param string $codeBucket
     *
     * @return void
     */
    public function haveCodeBucketEnv(string $codeBucket): void
    {
        $oldEnv = getenv(static::SPRYKER_CODE_BUCKET);

        putenv(sprintf('%s=%s', static::SPRYKER_CODE_BUCKET, $codeBucket));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($oldEnv): void {
            putenv(sprintf('%s=%s', static::SPRYKER_CODE_BUCKET, $oldEnv));
        });
    }
}
