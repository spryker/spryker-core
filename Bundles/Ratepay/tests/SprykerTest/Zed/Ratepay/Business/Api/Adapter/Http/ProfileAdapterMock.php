<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class ProfileAdapterMock extends AbstractAdapterMock
{
    /**
     * @return string
     */
    public function getSuccessResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <operation>PROFILE_REQUEST</operation>
                <response-type>PROFILE_SETTINGS</response-type>
                <external />
                <processing>
                    <timestamp>2016-05-26T10:35:28.000</timestamp>
                    <status code="OK">Successfully</status>
                    <reason code="306">Profile read successful</reason>
                    <result code="500">Profile processed</result>
                </processing>
            </head>
            <content>
                <master-data>
                    <profile-id>SPRYKER_TE_DEU</profile-id>
                    <merchant-name>Spryker</merchant-name>
                    <shop-name>Spryker</shop-name>
                    <merchant-status>2</merchant-status>
                    <activation-status-invoice>2</activation-status-invoice>
                    <activation-status-installment>2</activation-status-installment>
                    <activation-status-elv>2</activation-status-elv>
                    <activation-status-prepayment>2</activation-status-prepayment>
                    <eligibility-ratepay-invoice>yes</eligibility-ratepay-invoice>
                    <eligibility-ratepay-installment>yes</eligibility-ratepay-installment>
                    <eligibility-ratepay-elv>yes</eligibility-ratepay-elv>
                    <eligibility-ratepay-prepayment>yes</eligibility-ratepay-prepayment>
                    <eligibility-ratepay-pq-slim>yes</eligibility-ratepay-pq-slim>
                    <eligibility-ratepay-pq-light>yes</eligibility-ratepay-pq-light>
                    <eligibility-ratepay-pq-full>yes</eligibility-ratepay-pq-full>
                    <tx-limit-invoice-min>10</tx-limit-invoice-min>
                    <tx-limit-invoice-max>999999</tx-limit-invoice-max>
                    <tx-limit-invoice-max-b2b></tx-limit-invoice-max-b2b>
                    <tx-limit-installment-min>60</tx-limit-installment-min>
                    <tx-limit-installment-max>999999</tx-limit-installment-max>
                    <tx-limit-installment-max-b2b></tx-limit-installment-max-b2b>
                    <tx-limit-elv-min>10</tx-limit-elv-min>
                    <tx-limit-elv-max>999999</tx-limit-elv-max>
                    <tx-limit-elv-max-b2b></tx-limit-elv-max-b2b>
                    <tx-limit-prepayment-min>10</tx-limit-prepayment-min>
                    <tx-limit-prepayment-max>999999</tx-limit-prepayment-max>
                    <tx-limit-prepayment-max-b2b></tx-limit-prepayment-max-b2b>
                    <b2b-invoice>yes</b2b-invoice>
                    <delivery-address-invoice>yes</delivery-address-invoice>
                    <b2b-installment>no</b2b-installment>
                    <delivery-address-installment>yes</delivery-address-installment>
                    <b2b-elv>yes</b2b-elv>
                    <delivery-address-elv>yes</delivery-address-elv>
                    <b2b-prepayment>yes</b2b-prepayment>
                    <delivery-address-prepayment>yes</delivery-address-prepayment>
                    <b2b-PQ-slim>no</b2b-PQ-slim>
                    <delivery-address-PQ-slim>no</delivery-address-PQ-slim>
                    <b2b-PQ-light>no</b2b-PQ-light>
                    <delivery-address-PQ-light>no</delivery-address-PQ-light>
                    <b2b-PQ-full>no</b2b-PQ-full>
                    <delivery-address-PQ-full>no</delivery-address-PQ-full>
                    <eligibility-device-fingerprint>yes</eligibility-device-fingerprint>
                    <device-fingerprint-snippet-id>ratepay</device-fingerprint-snippet-id>
                    <country-code-billing>CH,AT,DE</country-code-billing>
                    <country-code-delivery>CH,AT,DE</country-code-delivery>
                    <currency>EUR,CHF</currency>
                </master-data>
                <installment-configuration-result name="SPRYKER_TE_DEU" type="DEFAULT">
                    <interestrate-min>13.7</interestrate-min>
                    <interestrate-default>13.7</interestrate-default>
                    <interestrate-max>13.7</interestrate-max>
                    <interest-rate-merchant-towards-bank>13.7</interest-rate-merchant-towards-bank>
                    <month-number-min>3</month-number-min>
                    <month-number-max>24</month-number-max>
                    <month-longrun>25</month-longrun>
                    <amount-min-longrun>1000</amount-min-longrun>
                    <month-allowed>3,6,12,24</month-allowed>
                    <valid-payment-firstdays>28</valid-payment-firstdays>
                    <payment-firstday>28</payment-firstday>
                    <payment-amount>60</payment-amount>
                    <payment-lastrate>0</payment-lastrate>
                    <rate-min-normal>20</rate-min-normal>
                    <rate-min-longrun>20</rate-min-longrun>
                    <service-charge>3.95</service-charge>
                    <min-difference-dueday>28</min-difference-dueday>
                </installment-configuration-result>
            </content>
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
                <operation>PROFILE_REQUEST</operation>
                <response-type>STATUS_ERROR</response-type>
                <external />
                <processing>
                    <timestamp>2016-05-26T10:35:28.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="307">Profile read successful</reason>
                    <result code="510">Profile processed</result>
                </processing>
            </head>
            <content />
        </response>';
    }
}
