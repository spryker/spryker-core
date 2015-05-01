<?php

namespace SprykerFeature\Zed\Payone\Business;


use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;


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

    public function processTransactionStatusUpdate(TransactionStatusRequest $request)
    {

    }

    protected function validate(TransactionStatusRequest $request)
    {

    }


}
