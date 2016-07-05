<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\FactFinder\Business\Api\Builder\BuilderFactory;
use Spryker\Zed\FactFinder\Business\Api\Model\RequestModelFactory;

class ApiRequestFactory extends AbstractBusinessFactory
{

    /**
     * @var \Spryker\Zed\FactFinder\Business\Api\Builder\BuilderFactory
     */
    protected $builderFactory;

    /**
     * ApiFactory constructor.
     *
     * @param \Spryker\Zed\FactFinder\Business\Api\Builder\BuilderFactory $builderFactory
     */
    public function __construct(BuilderFactory $builderFactory)
    {
        $this->builderFactory = $builderFactory;
    }

    /**
     *
     * @return \Spryker\Zed\FactFinder\Business\Api\Model\RequestModelFactoryInterface
     */
    public function createRequestModelFactory()
    {
        $factory = (new RequestModelFactory())
//            ->registerBuilder(ApiConstants::REQUEST_MODEL_PAYMENT_INIT, $this->createInitModel())
        ;
        return $factory;
    }

//    /**
//     * @return \Spryker\Zed\FactFinder\Business\Api\Model\Payment\Init
//     */
//    protected function createInitModel()
//    {
//        return new PaymentInit(
//            $this->builderFactory->createHead()
//        );
//    }

}
