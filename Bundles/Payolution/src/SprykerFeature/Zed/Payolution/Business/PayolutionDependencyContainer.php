<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

/**
 * @method Factory|PayolutionBusiness getFactory()
 *
 */
class PayolutionDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @return PaymentManagerInterface
     */
    public function createPaymentManager()
    {
        $paymentManager = $this->getFactory()
            ->createPaymentPaymentManager(
                $this->createExecutionAdapter(),
                $this->getQueryContainer()
            );

        return $paymentManager;
    }

    /**
     * @return AdapterInterface
     */
    protected function createExecutionAdapter()
    {
        return $this->getFactory()
            ->createApiAdapterHttpGuzzle(
                $this->createStandardParameter()->getPaymentGatewayUrl()
            );
    }

}
