<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class RefundAdapterMock extends AbstractAdapterMock
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
                <operation subtype="return">PAYMENT_CHANGE</operation>
                <response-type>PAYMENT_PERMISSION</response-type>
                <external>
                    <order-id>DE--77</order-id>
                </external>
                <processing>
                    <timestamp>2016-04-18T11:29:53.000</timestamp>
                    <status code="OK">Successfully</status>
                    <reason code="700">Request successful</reason>
                    <result code="403">Payment change successful</result>
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
                <transaction-id>17-201604182754010</transaction-id>
                <transaction-short-id>LQWQ.5P3Y.SW81.MAYH</transaction-short-id>
                <operation subtype="return">PAYMENT_CHANGE</operation>
                <response-type>STATUS_ERROR</response-type>
                <external>
                    <order-id>DE--81</order-id>
                </external>
                <processing>
                    <timestamp>2016-04-18T15:34:26.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="2300">Request basket not valid </reason>
                    <result code="150">Processing failed</result>
                </processing>
            </head>
            <content />
        </response>';
    }
}
