<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payolution\Session;

use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PayolutionSession implements PayolutionSessionInterface
{
    const PAYOLUTION_SESSION_IDENTIFIER = 'payolution session identifier';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer
     *
     * @return $this
     */
    public function setInstallmentPayments(PayolutionCalculationResponseTransfer $payolutionCalculationResponseTransfer)
    {
        $this->session->set(self::PAYOLUTION_SESSION_IDENTIFIER, $payolutionCalculationResponseTransfer);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasInstallmentPayments()
    {
        return $this->session->has(self::PAYOLUTION_SESSION_IDENTIFIER);
    }

    /**
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function getInstallmentPayments()
    {
        $payolutionCalculationResponseTransfer = new PayolutionCalculationResponseTransfer();

        if ($this->hasInstallmentPayments()) {
            return $this->session->get(self::PAYOLUTION_SESSION_IDENTIFIER, $payolutionCalculationResponseTransfer);
        }

        return $payolutionCalculationResponseTransfer;
    }

    /**
     * @return bool
     */
    public function removeInstallmentPayments()
    {
        if ($this->hasInstallmentPayments()) {
            $this->session->remove(self::PAYOLUTION_SESSION_IDENTIFIER);

            return true;
        }

        return false;
    }
}
