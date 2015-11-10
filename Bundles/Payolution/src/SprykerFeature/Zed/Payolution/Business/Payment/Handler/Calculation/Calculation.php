<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Handler\Calculation;

use DOMDocument;
use Generated\Shared\Payolution\PayolutionResponseInterface;
use Generated\Shared\Payolution\CheckoutRequestInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Handler\AbstractPaymentHandler;

class Calculation extends AbstractPaymentHandler implements CalculationInterface
{

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionResponseInterface
     */
    public function calculateInstallmentPayments(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        $paymentTransfer = $checkoutRequestTransfer->getPayolutionPayment();
        $requestData = $this
            ->getMethodMapper($paymentTransfer->getAccountBrand())
            ->buildCalculationRequest($checkoutRequestTransfer);

        return $this->sendRequest($requestData);
    }

    /**
     * @param string $requestData
     *
     * @return PayolutionResponseInterface
     */
    protected function sendRequest($requestData)
    {
        $requestData = $this->arrayToXml($requestData);
        $responseData = $this->executionAdapter->sendAuthorizedRequest(
            $requestData,
            $this->getConfig()->getCalculationUserLogin(),
            $this->getConfig()->getCalculationUserPassword());
        $responseTransfer = $this->responseConverter->fromArray($responseData);

        return $responseTransfer;
    }

    protected function arrayToXml(array $requestData)
    {
        $xml = new DOMDocument();

        $requestElement = $this->createXml($xml, $requestData);
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
        if ( empty( $data['name'] ) )
            return false;

        // Create the element
        $element_value = ( ! empty( $data['value'] ) ) ? $data['value'] : null;
        $element = $xml->createElement( $data['name'], $element_value );

        // Add any attributes
        if ( ! empty( $data['attributes'] ) && is_array( $data['attributes'] ) ) {
            foreach ( $data['attributes'] as $attribute_key => $attribute_value ) {
                $element->setAttribute( $attribute_key, $attribute_value );
            }
        }

        // Any other items in the data array should be child elements
        foreach ( $data as $data_key => $child_data ) {
            if ( ! is_numeric( $data_key ) )
                continue;

            $child = $this->createXml( $xml, $child_data );
            if ( $child )
                $element->appendChild( $child );
        }

        return $element;
    }

}
