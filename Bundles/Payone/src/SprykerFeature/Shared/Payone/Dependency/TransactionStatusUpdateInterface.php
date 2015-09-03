<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Payone\Dependency;

interface TransactionStatusUpdateInterface
{

    /**
     * @return int
     */
    public function getAccessid();

    /**
     * @return int
     */
    public function getAid();

    /**
     * @return string
     */
    public function getBalance();

    /**
     * @return string
     */
    public function getClearingtype();

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @return int
     */
    public function getCustomerid();

    /**
     * @return string
     */
    public function getFailedcause();

    /**
     * @return string
     */
    public function getInvoiceDate();

    /**
     * @return string
     */
    public function getInvoiceDeliverydate();

    /**
     * @return string
     */
    public function getInvoiceDeliveryenddate();

    /**
     * @return string
     */
    public function getInvoiceGrossamount();

    /**
     * @return string
     */
    public function getInvoiceid();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getMode();

    /**
     * @return string
     */
    public function getParam();

    /**
     * @return int
     */
    public function getPortalid();

    /**
     * @return int
     */
    public function getProductid();

    /**
     * @return string
     */
    public function getPrice();

    /**
     * @return string
     */
    public function getReceivable();

    /**
     * @return string
     */
    public function getReference();

    /**
     * @return string
     */
    public function getReminderlevel();

    /**
     * @return string
     */
    public function getSequencenumber();

    /**
     * @return string
     */
    public function getTxaction();

    /**
     * @return int
     */
    public function getTxid();

    /**
     * @return int
     */
    public function getTxtime();

    /**
     * @return int
     */
    public function getUserid();

    /**
     * @return string
     */
    public function getClearingBankaccount();

    /**
     * @return string
     */
    public function getClearingBankaccountholder();

    /**
     * @return string
     */
    public function getClearingBankbic();

    /**
     * @return string
     */
    public function getClearingBankcity();

    /**
     * @return string
     */
    public function getClearingBankcode();

    /**
     * @return string
     */
    public function getClearingBankcountry();

    /**
     * @return string
     */
    public function getClearingBankiban();

    /**
     * @return string
     */
    public function getClearingBankname();

    /**
     * @return string
     */
    public function getIban();

    /**
     * @return string
     */
    public function getBic();

    /**
     * @return string
     */
    public function getMandateIdentification();

    /**
     * @return string
     */
    public function getClearingDuedate();

    /**
     * @return string
     */
    public function getClearingAmount();

    /**
     * @return string
     */
    public function getCreditorIdentifier();

    /**
     * @return string
     */
    public function getClearingDate();

    /**
     * @return string
     */
    public function getClearingInstructionnote();

    /**
     * @return string
     */
    public function getClearingLegalnote();

    /**
     * @return string
     */
    public function getClearingReference();

}
