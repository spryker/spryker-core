<?php

namespace SprykerFeature\Zed\Payone\Business;


use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Payone\Transfer\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Transfer\CaptureDataInterface;
use SprykerFeature\Shared\Payone\Transfer\DebitDataInterface;
use SprykerFeature\Shared\Payone\Transfer\RefundDataInterface;
use SprykerFeature\Shared\Payone\Transfer\StandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use SprykerFeature\Zed\Payone\Business\Mapper\PaymentMethodMapperInterface;
use SprykerFeature\Zed\Payone\Business\Mode\ModeDetectorInterface;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;


class TransactionStatusManager
{

    /**
     * @var AutoCompletion
     */
    protected $locator;
    /**
     * @var PayoneQueryContainerInterface
     */
    protected $queryContainer;


    /**
     * @param LocatorLocatorInterface $locator
     * @param PayoneQueryContainerInterface $queryContainer
     */
    public function __construct(LocatorLocatorInterface $locator,
                                PayoneQueryContainerInterface $queryContainer)
    {
        $this->locator = $locator;
        $this->queryContainer = $queryContainer;
    }


}
