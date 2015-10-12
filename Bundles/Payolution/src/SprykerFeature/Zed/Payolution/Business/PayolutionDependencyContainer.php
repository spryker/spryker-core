<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionBusiness;
use SprykerFeature\Zed\CustomerCheckoutConnector\Business\CustomerOrderSaverInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\ConverterInterface as RequestConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Response\ConverterInterface as ResponseConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Log\TransactionStatusLogInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\CommunicatorInterface;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionOrderItem;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionRequestLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;
use Guzzle\Http\Client as GuzzleClient;

/**
 * @method PayolutionBusiness getFactory()
 * @method PayolutionQueryContainerInterface getQueryContainer()
 * @method PayolutionConfig getConfig()
 */
class PayolutionDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CommunicatorInterface
     */
    public function createPaymentCommunicator()
    {
        $paymentManager = $this->getFactory()->createPaymentCommunicator(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createRequestConverter(),
            $this->createResponseConverter(),
            $this
        );

        $paymentManager->registerMethodMapper(
            $this->getFactory()->createPaymentMethodMapperInvoice($this->getConfig())
        );
        $paymentManager->registerMethodMapper(
            $this->getFactory()->createPaymentMethodMapperInstallment($this->getConfig())
        );

        return $paymentManager;
    }

    /**
     * @return AdapterInterface
     */
    protected function createExecutionAdapter()
    {
        $gatewayUrl = $this->getConfig()->getGatewayUrl();

        $client = new GuzzleClient([
            'timeout' => $this->getConfig()->getDefaultTimeout(),
        ]);

        return $this->getFactory()->createApiAdapterHttpGuzzle($client, $gatewayUrl);
    }

    /**
     * @return CustomerOrderSaverInterface
     */
    public function createOrderSaver()
    {
        return $this->getFactory()->createOrderSaver($this);
    }

    /**
     * @return RequestConverterInterface
     */
    public function createRequestConverter()
    {
        return $this->getFactory()->createApiRequestConverter();
    }

    /**
     * @return ResponseConverterInterface
     */
    public function createResponseConverter()
    {
        return $this->getFactory()->createApiResponseConverter();
    }

    /**
     * @return TransactionStatusLogInterface
     */
    public function createTransactionStatusLog()
    {
        return $this->getFactory()->createLogTransactionStatusLog($this->getQueryContainer());
    }

}
