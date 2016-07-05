<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api;

class ApiFacade 
{

    /**
     * @var FFConnector
     */
    protected $ffConnector;

    /**
     *
     */
    public function __construct(FFConnector $ffConnector)
    {
        $this->ffConnector = $ffConnector;
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
