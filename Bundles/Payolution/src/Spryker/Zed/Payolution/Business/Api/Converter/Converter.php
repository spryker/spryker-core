<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Api\Converter;

use DOMDocument;
use DOMElement;
use Generated\Shared\Transfer\PayolutionCalculationInstallmentTransfer;
use Generated\Shared\Transfer\PayolutionCalculationPaymentDetailTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyInterface;

class Converter implements ConverterInterface
{
    /**
     * @var \Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyInterface $moneyFacade
     */
    public function __construct(PayolutionToMoneyInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param string $stringData
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function toTransactionResponseTransfer($stringData)
    {
        $arrayData = $this->stringToArray($stringData);
        $transactionResponseTransfer = $this->arrayToTransactionResponseTransfer($arrayData);

        return $transactionResponseTransfer;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function toCalculationRequest(array $data)
    {
        return $this->arrayToXml($data);
    }

    /**
     * @param string $stringData
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function toCalculationResponseTransfer($stringData)
    {
        return $this->xmlToCalculationResponseTransfer($stringData);
    }

    /**
     * @param string $stringData
     *
     * @return array
     */
    protected function stringToArray($stringData)
    {
        parse_str($stringData, $arrayData);

        return $arrayData;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    protected function arrayToTransactionResponseTransfer(array $data)
    {
        $responseTransfer = new PayolutionTransactionResponseTransfer();

        foreach ($data as $key => $value) {
            $convertedKey = str_replace(['_', '.'], ' ', $key);
            $convertedKey = mb_strtolower($convertedKey);
            $convertedKey = mb_convert_case($convertedKey, MB_CASE_UPPER, 'UTF-8');
            $convertedKey = str_replace(' ', '', $convertedKey);
            $methodName = 'set' . $convertedKey;

            if (method_exists($responseTransfer, $methodName) === false) {
                continue;
            }

            $responseTransfer->$methodName($value);
        }

        return $responseTransfer;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function arrayToXml(array $data)
    {
        $xml = new DOMDocument();

        $requestElement = $this->createXml($xml, $data);
        $xml->appendChild($requestElement);

        $xml->formatOutput = true;

        return $xml->saveXML();
    }

    /**
     * @param \DOMDocument $xml
     * @param array $data
     *
     * @return \DOMElement
     */
    protected function createXml(DOMDocument $xml, array $data)
    {
        $element = $this->createXmlElement($xml, $data);

        $element = $this->fillXmlAttributes($element, $data);

        foreach ($data as $dataKey => $childData) {
            if (is_numeric($dataKey) === false) {
                continue;
            }

            $child = $this->createXml($xml, $childData);
            if ($child !== false) {
                $element->appendChild($child);
            }
        }

        return $element;
    }

    /**
     * @param \DOMDocument $xml
     * @param array $data
     *
     * @return \DOMElement
     */
    protected function createXmlElement(DOMDocument $xml, array $data)
    {
        $elementValue = empty($data[ApiConstants::CALCULATION_XML_ELEMENT_VALUE]) === false ?
            $data[ApiConstants::CALCULATION_XML_ELEMENT_VALUE] :
            null;

        return $xml->createElement(
            $data[ApiConstants::CALCULATION_XML_ELEMENT_NAME],
            $elementValue
        );
    }

    /**
     * @param \DOMElement $element
     * @param array $data
     *
     * @return \DOMElement
     */
    protected function fillXmlAttributes(DOMElement $element, array $data)
    {
        if (empty($data[ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES]) === false &&
            is_array($data[ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES]) === true
        ) {
            foreach ($data[ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES] as $attributeKey => $attributeValue) {
                $element->setAttribute($attributeKey, $attributeValue);
            }
        }

        return $element;
    }

    /**
     * @param string $xmlString
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    protected function xmlToCalculationResponseTransfer($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        $arrayData = $this->xmlToArray($xml);

        $responseTransfer = (new PayolutionCalculationResponseTransfer())
            ->setVersion($arrayData['@attributes']['version'])
            ->setTransactionType($arrayData['TransactionType'])
            ->setPaymentType($arrayData['PaymentType'])
            ->setIdentificationTransactionid($arrayData['Identification']['TransactionID'])
            ->setIdentificationUniqueid($arrayData['Identification']['UniqueID'])
            ->setStatus($arrayData['Status'])
            ->setStatusCode($arrayData['StatusCode'])
            ->setDescription($arrayData['Description'])
            ->setTacUrl($arrayData['AdditionalInformation']['TacUrl'])
            ->setDataPrivacyConsentUrl($arrayData['AdditionalInformation']['DataPrivacyConsentUrl']);

        if (isset($arrayData['PaymentDetails']) === true) {
            foreach ($arrayData['PaymentDetails'] as $paymentDetail) {
                $paymentDetailTransfer = $this->arrayToCalculationPaymentDetailTransfer($paymentDetail);
                $responseTransfer->addPaymentDetail($paymentDetailTransfer);
            }
        }

        return $responseTransfer;
    }

    /**
     * @param string $xml
     *
     * @return array
     */
    protected function xmlToArray($xml)
    {
        return json_decode(json_encode($xml), true);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationPaymentDetailTransfer
     */
    protected function arrayToCalculationPaymentDetailTransfer(array $data)
    {
        $paymentDetailTransfer = (new PayolutionCalculationPaymentDetailTransfer())
            ->setOriginalAmount($this->decimalToInteger($data['OriginalAmount']))
            ->setTotalAmount($this->decimalToInteger($data['TotalAmount']))
            ->setMinimumInstallmentFee($this->decimalToInteger($data['MinimumInstallmentFee']))
            ->setDuration($data['Duration'])
            ->setInterestRate($this->decimalToInteger($data['InterestRate']))
            ->setEffectiveInterestRate($this->decimalToInteger($data['EffectiveInterestRate']))
            ->setUsage($data['Usage'])
            ->setCurrency($data['Currency'])
            ->setStandardCreditInformationUrl($data['StandardCreditInformationUrl']);

        if (isset($data['Installment']) === true) {
            foreach ($data['Installment'] as $installment) {
                $installmentTransfer = $this->arrayToCalculationInstallmentTransfer($installment);
                $paymentDetailTransfer->addInstallment($installmentTransfer);
            }
        }

        return $paymentDetailTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationInstallmentTransfer
     */
    protected function arrayToCalculationInstallmentTransfer(array $data)
    {
        $data['Amount'] = $this->decimalToInteger($data['Amount']);

        return (new PayolutionCalculationInstallmentTransfer())->fromArray($data);
    }

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function decimalToInteger($amount)
    {
        return $this->moneyFacade->convertDecimalToInteger((float)$amount);
    }
}
