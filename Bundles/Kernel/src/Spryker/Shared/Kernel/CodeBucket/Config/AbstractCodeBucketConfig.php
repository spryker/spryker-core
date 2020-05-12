<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\CodeBucket\Config;

use Spryker\Shared\Kernel\CodeBucket\Exception\InvalidCodeBucketException;

abstract class AbstractCodeBucketConfig implements CodeBucketConfigInterface
{
    protected const SPRYKER_CODE_BUCKET = 'SPRYKER_CODE_BUCKET';

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string
    {
        $codeBucket = getenv(static::SPRYKER_CODE_BUCKET);

        if ($codeBucket === false) {
            $codeBucket = $this->getDefaultCodeBucket();
        }

        $this->assertCodeBucket($codeBucket);

        return $codeBucket;
    }

    /**
     * @return string[]
     */
    abstract public function getCodeBuckets(): array;

    /**
     * @param string $codeBucket
     *
     * @throws \Spryker\Shared\Kernel\CodeBucket\Exception\InvalidCodeBucketException
     *
     * @return void
     */
    protected function assertCodeBucket(string $codeBucket): void
    {
        if ($codeBucket !== '' && !in_array($codeBucket, $this->getCodeBuckets())) {
            throw new InvalidCodeBucketException(sprintf('CodeBucket "%s" is not a valid option!', $codeBucket));
        }
    }

    /**
     * @return string
     */
    protected function getDefaultCodeBucket(): string
    {
        return '';
    }
}
