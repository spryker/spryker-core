<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class CancelAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <transaction-id>07-201604182754078</transaction-id>
                <transaction-short-id>F485.93PN.6QO7.4Q2V</transaction-short-id>
                <operation subtype="cancellation">PAYMENT_CHANGE</operation>
                <response-type>PAYMENT_PERMISSION</response-type>
                <external>
                    <order-id>DE--83</order-id>
                </external>
                <processing>
                    <timestamp>2016-04-18T15:43:37.000</timestamp>
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
                <transaction-id>31-201604182754070</transaction-id>
                <transaction-short-id>TM3L.6BZA.GS4Y.7SE8</transaction-short-id>
                <operation subtype="cancellation">PAYMENT_CHANGE</operation>
                <response-type>STATUS_ERROR</response-type>
                <external>
                    <order-id>DE--82</order-id>
                </external>
                <processing>
                    <timestamp>2016-04-18T15:44:38.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="2300">Request basket not valid </reason>
                    <result code="150">Processing failed</result>
                </processing>
            </head>
            <content />
        </response>';
    }
}
