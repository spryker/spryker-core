<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class ConfirmDeliveryAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <transaction-id>20-201604182752942</transaction-id>
                <transaction-short-id>R15F.5NPF.T2PJ.2O50</transaction-short-id>
                <operation>CONFIRMATION_DELIVER</operation>
                <response-type>STATUS_RESPONSE</response-type>
                <external />
                <processing>
                    <timestamp>2016-04-18T11:28:49.000</timestamp>
                    <status code="OK">Successfully</status>
                    <reason code="303">No RMS reason code</reason>
                    <result code="404">Confirmation deliver successful</result>
                </processing>
            </head>
            <content />
        </response>';
    }

    /**
     * @return array
     */
    public function getFailureResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <transaction-id>20-201604182752942</transaction-id>
                <transaction-short-id>R15F.5NPF.T2PJ.2O50</transaction-short-id>
                <operation>CONFIRMATION_DELIVER</operation>
                 <response-type>STATUS_ERROR</response-type>
                <external />
                <processing>
                    <timestamp>2016-04-18T11:54:08.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="200">Validation failed</reason>
                    <result code="150">Processing failed</result>
                    <customer-message>Error message 1.</customer-message>
                </processing>
            </head>
            <content />
        </response>';
    }
}
