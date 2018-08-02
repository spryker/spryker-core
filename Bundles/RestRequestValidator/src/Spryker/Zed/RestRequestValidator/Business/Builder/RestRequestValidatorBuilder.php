<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Builder;

use Spryker\Zed\RestRequestValidator\Business\Cacher\RestRequestValidatorCacherInterface;
use Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface;
use Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface;

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
     * @var \Spryker\Zed\RestRequestValidator\Business\Cacher\RestRequestValidatorCacherInterface
     */
    protected $validatorCacher;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface $validatorCollector
     * @param \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface $validatorMerger
     * @param \Spryker\Zed\RestRequestValidator\Business\Cacher\RestRequestValidatorCacherInterface $validatorCacher
     */
    public function __construct(
        RestRequestValidatorCollectorInterface $validatorCollector,
        RestRequestValidatorMergerInterface $validatorMerger,
        RestRequestValidatorCacherInterface $validatorCacher
    ) {
        $this->validatorCollector = $validatorCollector;
        $this->validatorMerger = $validatorMerger;
        $this->validatorCacher = $validatorCacher;
    }

    /**
     * @returns void
     *
     * @return void
     */
    public function build(): void
    {
        $config = $this->validatorCollector->collect();
        $config = $this->validatorMerger->merge($config);
        $this->validatorCacher->store($config);
    }
}
