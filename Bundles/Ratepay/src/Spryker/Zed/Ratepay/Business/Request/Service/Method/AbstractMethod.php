<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Service\Method;

use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface;
use Spryker\Zed\Ratepay\Business\Request\RequestMethodInterface;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;

abstract class AbstractMethod implements RequestMethodInterface
{
    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    protected $modelFactory;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected $mapperFactory;

    /**
     * @var \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface $modelFactory
     * @param \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory $mapperFactory
     * @param \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    public function __construct(
        RequestModelFactoryInterface $modelFactory,
        MapperFactory $mapperFactory,
        RatepayQueryContainerInterface $queryContainer
    ) {

        $this->modelFactory = $modelFactory;
        $this->mapperFactory = $mapperFactory;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return void
     */
    protected function mapHeadData()
    {
        $this->mapperFactory
            ->getHeadMapper()
            ->map();
    }
}
