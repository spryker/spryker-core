<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Builder;

use Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCacheCollectorInterface;
use Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorSchemaMergerInterface;
use Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorCacheSaverInterface;

class RestRequestValidatorCodeBucketCacheBuilder implements RestRequestValidatorCodeBucketCacheBuilderInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCacheCollectorInterface
     */
    protected $restRequestValidatorCacheCollector;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorSchemaMergerInterface
     */
    protected $restRequestValidatorSchemaMerger;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorCacheSaverInterface
     */
    protected $restRequestValidatorCacheSaver;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCacheCollectorInterface $restRequestValidatorCacheCollector
     * @param \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorSchemaMergerInterface $restRequestValidatorSchemaMerger
     * @param \Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorCacheSaverInterface $restRequestValidatorCacheSaver
     */
    public function __construct(
        RestRequestValidatorCacheCollectorInterface $restRequestValidatorCacheCollector,
        RestRequestValidatorSchemaMergerInterface $restRequestValidatorSchemaMerger,
        RestRequestValidatorCacheSaverInterface $restRequestValidatorCacheSaver
    ) {
        $this->restRequestValidatorCacheCollector = $restRequestValidatorCacheCollector;
        $this->restRequestValidatorSchemaMerger = $restRequestValidatorSchemaMerger;
        $this->restRequestValidatorCacheSaver = $restRequestValidatorCacheSaver;
    }

    /**
     * @param string $codeBucket
     *
     * @return void
     */
    public function buildCacheForCodeBucket(string $codeBucket): void
    {
        $config = $this->restRequestValidatorCacheCollector->collect($codeBucket);
        $config = $this->restRequestValidatorSchemaMerger->merge($config);
        $this->restRequestValidatorCacheSaver->saveCacheForCodeBucket($config, $codeBucket);
    }
}
