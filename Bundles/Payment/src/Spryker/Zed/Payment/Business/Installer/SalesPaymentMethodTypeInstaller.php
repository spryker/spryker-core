<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Installer;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Payment\PaymentConfig;
use Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface;

class SalesPaymentMethodTypeInstaller implements SalesPaymentMethodTypeInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Payment\PaymentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Payment\PaymentConfig $config
     */
    public function __construct(
        PaymentEntityManagerInterface $entityManager,
        PaymentConfig $config
    ) {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {
            $this->executeInstallTransaction();
        });
    }

    /**
     * @return void
     */
    protected function executeInstallTransaction(): void
    {
        $salesPaymentMethods = $this->config->getSalesPaymentMethodTypes();

        foreach ($salesPaymentMethods as $paymentProvider => $paymentMethods) {
            foreach ($paymentMethods as $paymentMethod) {
                $salesPaymentMethodTypeTransfer = (new SalesPaymentMethodTypeTransfer())
                    ->setPaymentMethod((new PaymentMethodTransfer())->setMethodName($paymentMethod))
                    ->setPaymentProvider((new PaymentProviderTransfer())->setName($paymentProvider));
                $this->entityManager
                    ->saveSalesPaymentMethodTypeByPaymentProviderAndMethod($salesPaymentMethodTypeTransfer);
            }
        }
    }
}
