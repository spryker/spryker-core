<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2020-03-24
 * Time: 17:18
 */

namespace Spryker\Shared\Kernel\CodeBucket\Context;


use Spryker\Shared\Kernel\Store;

class DefaultCodeBucketContext extends AbstractCodeBucketContext implements CodeBucketContextInterface
{
    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        if (!Store::isDynamicStoreMode()) {
            return Store::getInstance()->getAllowedStores();
        }

        return [];
    }

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string
    {
        if (!Store::isDynamicStoreMode()) {
            return Store::getInstance()->getStoreName();
        }

        return '';
    }
}
