<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

class ConverterFactory
{
    /**
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     */
    public function __construct(RatepayToMoneyInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\TransferObjectConverter
     */
    public function getTransferObjectConverter(
        ResponseInterface $response
    ) {
        return new TransferObjectConverter(
            $response,
            $this->moneyFacade
        );
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse $response
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\InstallmentCalculationResponseConverter
     */
    public function getInstallmentCalculationResponseConverter(
        CalculationResponse $response,
        Calculation $request
    ) {
        return new InstallmentCalculationResponseConverter(
            $response,
            $this->moneyFacade,
            $this->getTransferObjectConverter($response),
            $request
        );
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse $response
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\InstallmentConfigurationResponseConverter
     */
    public function getInstallmentConfigurationResponseConverter(
        ConfigurationResponse $response,
        Configuration $request
    ) {
        return new InstallmentConfigurationResponseConverter(
            $response,
            $this->moneyFacade,
            $this->getTransferObjectConverter($response),
            $request
        );
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\ProfileResponseConverter
     */
    public function getProfileResponseConverter(
        ResponseInterface $response
    ) {
        return new ProfileResponseConverter(
            $response,
            $this->moneyFacade,
            $this->getTransferObjectConverter($response)
        );
    }
}
