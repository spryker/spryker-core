<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Process\Steps;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Client\Checkout\CheckoutClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\AbstractBaseStep;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithPostConditionErrorRouteInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractPlaceOrderStep extends AbstractBaseStep implements StepWithExternalRedirectInterface, StepWithPostConditionErrorRouteInterface
{
    /**
     * @var \Spryker\Client\Checkout\CheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var string
     */
    protected $externalRedirectUrl;

    /**
     * @var \Generated\Shared\Transfer\CheckoutResponseTransfer|null
     */
    protected $checkoutResponseTransfer;

    /**
     * @var array
     */
    protected $errorCodeToRouteMatching = [];

    /**
     * @var string|null
     */
    protected $postConditionErrorRoute;

    /**
     * @param \Spryker\Client\Checkout\CheckoutClientInterface $checkoutClient
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param array $errorCodeToRouteMatching
     */
    public function __construct(
        CheckoutClientInterface $checkoutClient,
        $stepRoute,
        $escapeRoute,
        array $errorCodeToRouteMatching = []
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->checkoutClient = $checkoutClient;
        $this->errorCodeToRouteMatching = $errorCodeToRouteMatching;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $checkoutResponseTransfer = $this->checkoutClient->placeOrder($quoteTransfer);

        if ($checkoutResponseTransfer->getIsExternalRedirect()) {
            $this->externalRedirectUrl = $checkoutResponseTransfer->getRedirectUrl();
        }

        if ($checkoutResponseTransfer->getSaveOrder() !== null) {
            $quoteTransfer->setOrderReference($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
        }

        $this->setCheckoutErrorMessages($checkoutResponseTransfer);
        $this->checkoutResponseTransfer = $checkoutResponseTransfer;

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if ($this->checkoutResponseTransfer && !$this->checkoutResponseTransfer->getIsSuccess()) {
            $this->setPostConditionErrorRoute($this->checkoutResponseTransfer);

            return false;
        }

        return ($quoteTransfer->getOrderReference() !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    abstract protected function setCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * @return string
     */
    public function getExternalRedirectUrl()
    {
        return $this->externalRedirectUrl;
    }

    /**
     * @return string|null
     */
    public function getPostConditionErrorRoute()
    {
        return $this->postConditionErrorRoute;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function setPostConditionErrorRoute(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        foreach ($checkoutResponseTransfer->getErrors() as $error) {
            if (isset($this->errorCodeToRouteMatching[$error->getErrorCode()])) {
                $this->postConditionErrorRoute = $this->errorCodeToRouteMatching[$error->getErrorCode()];
            }
        }
    }
}
