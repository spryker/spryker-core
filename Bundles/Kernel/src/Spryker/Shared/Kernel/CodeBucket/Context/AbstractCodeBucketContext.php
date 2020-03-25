<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2020-03-24
 * Time: 17:00
 */

namespace Spryker\Shared\Kernel\CodeBucket\Context;


use Spryker\Shared\Kernel\CodeBucket\Exception\InvalidCodeBucketException;

abstract class AbstractCodeBucketContext
{
    protected const SPRYKER_CODE_BUCKET = 'SPRYKER_CODE_BUCKET';

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string
    {
        $codeBucket = getenv(static::SPRYKER_CODE_BUCKET);

        if ($codeBucket === false) {
            return $this->getDefaultCodeBucket();
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
        if (!in_array($codeBucket, $this->getCodeBuckets())) {
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
