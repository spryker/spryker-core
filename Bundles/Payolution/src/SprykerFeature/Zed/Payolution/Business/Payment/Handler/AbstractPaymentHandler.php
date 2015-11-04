<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Handler;

use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Response\ConverterInterface as ResponseConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Exception\NoMethodMapperException;
use SprykerFeature\Zed\Payolution\Business\Exception\OrderGrandTotalException;
use Generated\Shared\Payolution\PayolutionResponseInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\installment\InstallmentInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\invoice\InvoiceInterface;
use SprykerFeature\Zed\Payolution\PayolutionConfig;

abstract class AbstractPaymentHandler
{

    /**
     * @var AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var ResponseConverterInterface
     */
    protected $responseConverter;

    /**
     * @var PayolutionConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $methodMappers = [];

    /**
     * @param AdapterInterface $executionAdapter
     * @param ResponseConverterInterface $responseConverter
     * @param PayolutionConfig $config
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        ResponseConverterInterface $responseConverter,
        PayolutionConfig $config
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->responseConverter = $responseConverter;
        $this->config = $config;
    }

    /**
     * @return PayolutionConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param InvoiceInterface | InstallmentInterface $mapper
     *
     * @return void
     */
    public function registerMethodMapper($mapper)
    {
        $this->methodMappers[$mapper->getAccountBrand()] = $mapper;
    }

    /**
     * @param string $accountBrand
     *
     * @throws NoMethodMapperException
     *
     * @return InvoiceInterface | InstallmentInterface
     */
    protected function getMethodMapper($accountBrand)
    {
        if (!isset($this->methodMappers[$accountBrand])) {
            throw new NoMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$accountBrand];
    }

    /**
     * @param int $amount
     *
     * @throws OrderGrandTotalException
     *
     * @return void
     */
    private function checkMaxMinGrandTotal($amount)
    {
        if ($amount < $this->config->getMinOrderGrandTotalInvoice())
        {
            throw new OrderGrandTotalException('The grand total is less than the allowed minimum amount');
        }

        if ($amount > $this->config->getMaxOrderGrandTotalInvoice())
        {
            throw new OrderGrandTotalException('The grand total is greater than the allowed maximum amount');
        }
    }

    /**
     * @param array | string $requestData
     *
     * @return PayolutionResponseInterface
     */
    abstract protected function sendRequest($requestData);

}
