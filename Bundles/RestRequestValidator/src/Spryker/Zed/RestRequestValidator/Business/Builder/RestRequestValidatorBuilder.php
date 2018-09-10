<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Builder;

use Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface;
use Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface;
use Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaverInterface;
use Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface;

class RestRequestValidatorBuilder implements RestRequestValidatorBuilderInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface
     */
    protected $validatorCollector;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface
     */
    protected $validatorMerger;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaverInterface
     */
    protected $validatorSaver;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface $validatorCollector
     * @param \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface $validatorMerger
     * @param \Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaverInterface $validatorSaver
     * @param \Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        RestRequestValidatorCollectorInterface $validatorCollector,
        RestRequestValidatorMergerInterface $validatorMerger,
        RestRequestValidatorSaverInterface $validatorSaver,
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
            $this->validatorSaver->store($config, $store);
        }
    }
}
