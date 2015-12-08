<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Converter;

use Generated\Shared\Transfer\PayolutionCalculationInstallmentTransfer;
use Generated\Shared\Transfer\PayolutionCalculationPaymentDetailTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use SprykerFeature\Shared\Library\Currency\CurrencyManager;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\ApiConstants;
use DOMDocument;
use DOMElement;

class Converter implements ConverterInterface
{

    /**
     * @param string $stringData
     *
     * @return PayolutionTransactionResponseTransfer
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
     * @return PayolutionTransactionResponseTransfer
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
     * @return PayolutionTransactionResponseTransfer
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
     * @param DOMDocument $xml
     * @param array $data
     *
     * @return DOMDocument
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
     * @param DOMDocument $xml
     * @param array $data
     *
     * @return DOMElement
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
     * @param DOMElement $element
     * @param array $data
     *
     * @return DOMElement
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
     * @return PayolutionCalculationResponseTransfer
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
     * @return PayolutionCalculationPaymentDetailTransfer
     */
    protected function arrayToCalculationPaymentDetailTransfer(array $data)
    {
        $paymentDetailTransfer = (new PayolutionCalculationPaymentDetailTransfer())
            ->setOriginalAmount($this->centsToDecimal($data['OriginalAmount']))
            ->setTotalAmount($this->centsToDecimal($data['TotalAmount']))
            ->setMinimumInstallmentFee($this->centsToDecimal($data['MinimumInstallmentFee']))
            ->setDuration($data['Duration'])
            ->setInterestRate($this->centsToDecimal($data['InterestRate']))
            ->setEffectiveInterestRate($this->centsToDecimal($data['EffectiveInterestRate']))
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
     * @return PayolutionCalculationInstallmentTransfer
     */
    protected function arrayToCalculationInstallmentTransfer(array $data)
    {
        $data['Amount'] = $this->centsToDecimal($data['Amount']);

        return (new PayolutionCalculationInstallmentTransfer())->fromArray($data);
    }

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function centsToDecimal($amount)
    {
        return CurrencyManager::getInstance()->convertDecimalToCent($amount);
    }

}
