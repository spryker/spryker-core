<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Converter;

use Generated\Shared\Payolution\PayolutionTransactionResponseInterface;
use Generated\Shared\Payolution\PayolutionCalculationResponseInterface;
use Generated\Shared\Payolution\PayolutionCalculationInstallmentInterface;
use Generated\Shared\Payolution\PayolutionCalculationPaymentDetailInterface;
use Generated\Shared\Transfer\PayolutionCalculationInstallmentTransfer;
use Generated\Shared\Transfer\PayolutionCalculationPaymentDetailTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\ApiConstants;
use DOMDocument;

class Converter implements ConverterInterface
{

    /**
     * @param string $stringData
     *
     * @return PayolutionTransactionResponseInterface
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
     * @return PayolutionTransactionResponseInterface
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
     * @return PayolutionTransactionResponseInterface
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
    public function arrayToXml(array $data)
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
        $element_value = empty($data[ApiConstants::CALCULATION_XML_ELEMENT_VALUE]) === false ?
            $data[ApiConstants::CALCULATION_XML_ELEMENT_VALUE] :
            null;
        $element = $xml->createElement(
            $data[ApiConstants::CALCULATION_XML_ELEMENT_NAME],
            $element_value
        );

        if (empty($data[ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES]) === false &&
            is_array($data[ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES]) === true
        ) {
            foreach ($data[ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES] as $attribute_key => $attribute_value) {
                $element->setAttribute($attribute_key, $attribute_value);
            }
        }

        foreach ($data as $data_key => $child_data) {
            if (is_numeric($data_key) === false) {
                continue;
            }

            $child = $this->createXml($xml, $child_data);
            if ($child) {
                $element->appendChild($child);
            }
        }

        return $element;
    }

    /**
     * @param string $xmlString
     *
     * @return PayolutionCalculationResponseInterface
     */
    public function xmlToCalculationResponseTransfer($xmlString)
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
     * @return PayolutionCalculationPaymentDetailInterface
     */
    protected function arrayToCalculationPaymentDetailTransfer(array $data)
    {
        $paymentDetailTransfer = (new PayolutionCalculationPaymentDetailTransfer())
            ->setOriginalAmount($data['OriginalAmount'])
            ->setTotalAmount($data['TotalAmount'])
            ->setMinimumInstallmentFee($data['MinimumInstallmentFee'])
            ->setDuration($data['Duration'])
            ->setInterestRate($data['InterestRate'])
            ->setEffectiveInterestRate($data['EffectiveInterestRate'])
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
     * @return PayolutionCalculationInstallmentInterface
     */
    protected function arrayToCalculationInstallmentTransfer(array $data)
    {
        return (new PayolutionCalculationInstallmentTransfer())->fromArray($data);
    }

}
