<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2020-03-24
 * Time: 17:23
 */

namespace Spryker\Shared\Kernel\CodeBucket\Context;

interface CodeBucketContextInterface
{
    /**
     * @return string[]
     */
    public function getCodeBuckets(): array;

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string;
}
