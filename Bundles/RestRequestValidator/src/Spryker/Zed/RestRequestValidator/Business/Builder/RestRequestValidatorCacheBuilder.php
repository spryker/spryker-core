<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Builder;

use Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCacheCollectorInterface;
use Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorSchemaMergerInterface;
use Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorCacheSaverInterface;
use Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface;

class RestRequestValidatorCacheBuilder implements RestRequestValidatorCacheBuilderInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCacheCollectorInterface
     */
    protected $validatorCollector;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorSchemaMergerInterface
     */
    protected $validatorMerger;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorCacheSaverInterface
     */
    protected $validatorSaver;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCacheCollectorInterface $validatorCollector
     * @param \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorSchemaMergerInterface $validatorMerger
     * @param \Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorCacheSaverInterface $validatorSaver
     * @param \Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        RestRequestValidatorCacheCollectorInterface $validatorCollector,
        RestRequestValidatorSchemaMergerInterface $validatorMerger,
        RestRequestValidatorCacheSaverInterface $validatorSaver,
        RestRequestValidatorToStoreFacadeInterface $storeFacade
    ) {
        $this->validatorCollector = $validatorCollector;
        $this->validatorMerger = $validatorMerger;
        $this->validatorSaver = $validatorSaver;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return void
     */
    public function build(): void
    {
        foreach ($this->storeFacade->getAllStores() as $store) {
            $config = $this->validatorCollector->collect($store);
            $config = $this->validatorMerger->merge($config);
            $this->validatorSaver->save($config, $store);
        }
    }
}
