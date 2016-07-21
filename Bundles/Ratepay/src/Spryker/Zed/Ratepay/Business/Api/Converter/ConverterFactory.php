<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;

class ConverterFactory
{

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
            $this->createCurrencyManager()
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
            $this->createCurrencyManager(),
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
            $this->createCurrencyManager(),
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
            $this->createCurrencyManager(),
            $this->getTransferObjectConverter($response)
        );
    }

    /**
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected function createCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

}
