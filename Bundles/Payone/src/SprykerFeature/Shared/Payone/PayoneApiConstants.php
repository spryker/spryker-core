<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Payone;

interface PayoneApiConstants
{

    // GENERAL

    const PROVIDER_NAME = 'payone';

    // MODE

    const MODE_TEST = 'test';
    const MODE_LIVE = 'live';

    // VERSIONS

    const API_VERSION_3_8 = '3.8';
    const API_VERSION_3_9 = '3.9';

    // PAYMENT METHODS

    // credit/debit card methods
    const PAYMENT_METHOD_CREDITCARD = 'payment.payone.creditcard';
    const PAYMENT_METHOD_CREDITCARD_PSEUDO = 'payment.payone.creditcardpseudo';

    // e-wallet methods
    const PAYMENT_METHOD_E_WALLET = 'payment.payone.e_wallet';

    /**
     * @deprecated //TODO: To be removed
     */
    const PAYMENT_METHOD_PAYPAL = 'payment.payone.paypal';

    // bank account based methods
    const PAYMENT_METHOD_DIRECT_DEBIT = 'payment.payone.direct_debit';
    const PAYMENT_METHOD_INVOICE = 'payment.payone.invoice';
    const PAYMENT_METHOD_PREPAYMENT = 'payment.payone.prepayment';
    const PAYMENT_METHOD_CASH_ON_DELIVERY = 'payment.payone.cash_on_delivery';

    // online transfer methods
    const PAYMENT_METHOD_ONLINE_BANK_TRANSFER = 'payment.payone.online_bank_transfer';

    /**
     * @deprecated //TODO: To be removed
     */
    const PAYMENT_METHOD_SOFORT_UEBERWEISUNG = 'payment.payone.sofort_ueberweisung';
    /**
     * @deprecated //TODO: To be removed
     */
    const PAYMENT_METHOD_EPS = 'payment.payone.eps';
    /**
     * @deprecated //TODO: To be removed
     */
    const PAYMENT_METHOD_POST_FINANCE_CARD = 'payment.payone.post_finance_card';
    /**
     * @deprecated //TODO: To be removed
     */
    const PAYMENT_METHOD_POST_FINANCE_EFINANCE = 'payment.payone.post_finance_efinance';
    /**
     * @deprecated //TODO: To be removed
     */
    const PAYMENT_METHOD_GIROPAY = 'payment.payone.giropay';

    // financing methods
    const PAYMENT_METHOD_COMMERZ_FINANCE = 'payment.payone.commerz_finance';

    // gesicherter rechnunskauf
    const PAYMENT_METHOD_BILLSAVE = 'payment.payone.billsave';

    // CLEARING TYPE

    const CLEARING_TYPE_DIRECT_DEBIT = 'elv';
    const CLEARING_TYPE_CREDIT_CARD = 'cc';
    const CLEARING_TYPE_PREPAYMENT = 'vor';
    const CLEARING_TYPE_INVOICE = 'rec';
    const CLEARING_TYPE_ONLINE_BANK_TRANSFER = 'sb';
    const CLEARING_TYPE_CASH_ON_DELIVERY = 'cod';
    const CLEARING_TYPE_E_WALLET = 'wlt';
    const CLEARING_TYPE_FINANCING = 'fnc';

    // TXACTION

    // Defined in TransactionStatusConstants


    // WALLET TYPE

    const E_WALLET_TYPE_PAYPAL = 'PPE';

    // USE CUSTOMER DATA

    const USE_CUSTOMER_DATA_YES = 'yes';
    const USE_CUSTOMER_DATA_NO = 'no';

    // STORE CARD DATA

    const STORE_CARD_DATA_YES = 'yes';
    const STORE_CARD_DATA_NO = 'no';

    // SHIPPING PROVIDER

    const SHIPPING_PROVIDER_DHL = 'DHL';
    const SHIPPING_PROVIDER_BARTOLINI = 'BRT';

    // FINANCING SETTLE ACCOUNT

    const SETTLE_ACCOUNT_YES = 'yes';
    const SETTLE_ACCOUNT_NO = 'no';
    const SETTLE_ACCOUNT_AUTO = 'auto';

    // RESPONSE TYPE

    const RESPONSE_TYPE_APPROVED = 'APPROVED';
    const RESPONSE_TYPE_REDIRECT = 'REDIRECT';
    const RESPONSE_TYPE_VALID = 'VALID';
    const RESPONSE_TYPE_INVALID = 'INVALID';
    const RESPONSE_TYPE_BLOCKED = 'BLOCKED';
    const RESPONSE_TYPE_ENROLLED = 'ENROLLED';
    const RESPONSE_TYPE_ERROR = 'ERROR';

    // REQUEST ENCODING

    const REQUEST_ENCODING = 'UTF-8';

    // REQUEST TYPE

    const REQUEST_TYPE_PREAUTHORIZATION = 'preauthorization';
    const REQUEST_TYPE_AUTHORIZATION = 'authorization';
    const REQUEST_TYPE_CAPTURE = 'capture';
    const REQUEST_TYPE_REFUND = 'refund';
    const REQUEST_TYPE_DEBIT = 'debit';
    const REQUEST_TYPE_3DSECURE_CHECK = '3dscheck';
    const REQUEST_TYPE_ADDRESSCHECK = 'addresscheck';
    const REQUEST_TYPE_CONSUMERSCORE = 'consumerscore';
    const REQUEST_TYPE_BANKACCOUNTCHECK = 'bankaccountcheck';
    const REQUEST_TYPE_CREDITCARDCHECK = 'creditcardcheck';
    const REQUEST_TYPE_GETINVOICE = 'getinvoice';

    // ONLINE BANK TRANSFER TYPE

    const ONLINE_BANK_TRANSFER_TYPE_INSTANT_MONEY_TRANSFER = 'PNT';
    const ONLINE_BANK_TRANSFER_TYPE_GIROPAY = 'GPY';
    const ONLINE_BANK_TRANSFER_TYPE_EPS_ONLINE_BANK_TRANSFER = 'EPS';
    const ONLINE_BANK_TRANSFER_TYPE_POSTFINANCE_EFINANCE = 'PFF';
    const ONLINE_BANK_TRANSFER_TYPE_POSTFINANCE_CARD = 'PFC';
    const ONLINE_BANK_TRANSFER_TYPE_IDEAL = 'IDL';

    // FAILED CAUSE

    const FAILED_CAUSE_INSUFFICIENT_FUNDS = 'soc';           // soc Insufficient funds
    const FAILED_CAUSE_ACCOUNT_EXPIRED = 'cka';              // cka Account expired
    const FAILED_CAUSE_UNKNOWN_ACCOUNT_NAME = 'uan';         // uan Account no. / name not idential, incorrect or savings account
    const FAILED_CAUSE_NO_DIRECT_DEBIT = 'ndd';              // ndd No direct debit
    const FAILED_CAUSE_RECALL = 'rcl';                       // rcl Recall
    const FAILED_CAUSE_OBJECTION = 'obj';                    // obj Objection
    const FAILED_CAUSE_RETURNS = 'ret';                      // ret Return
    const FAILED_CAUSE_DEBIT_NOT_COLLECTABLE = 'nelv';       // nelv Debit cannot be collected
    const FAILED_CAUSE_CREDITCARD_CHARGEBACK = 'cb';         // cb Credit card chargeback
    const FAILED_CAUSE_CREDITCARD_NOT_COLLECTABLE = 'ncc';   // ncc Credit card cannot be collected


    // INVOICING ITEM TYPE

    const INVOICING_ITEM_TYPE_GOODS = 'goods';
    const INVOICING_ITEM_TYPE_SHIPMENT = 'shipment';
    const INVOICING_ITEM_TYPE_HANDLING = 'handling';
    const INVOICING_ITEM_TYPE_VOUCHER = 'voucher';

    // DELIVERY MODE

    const DELIVERY_MODE_POST = 'M';
    const DELIVERY_MODE_PDF = 'P';
    const DELIVERY_MODE_NONE = 'N';

    // FINANCING TYPE

    const FINANCING_TYPE_BSV = 'BSV'; // BILLSAFE
    const FINANCING_TYPE_CFR = 'CFR'; // COMMERZ FINANZ


    // ECOMMERCE MODE

    const ECOMMERCE_MODE_INTERNET = 'internet';
    const ECOMMERCE_MODE_SECURE3D = '3dsecure';
    const ECOMMERCE_MODE_MOTO = 'moto';

    // DEBIT TRANSACTION TYPE

    const DEBIT_TRANSACTION_TYPE_DIRECT_DEBIT_REFUND_FEE = 'RL'; //RL: Rücklastschriftgebühr
    const DEBIT_TRANSACTION_TYPE_DUNNING_CHARGE = 'MG'; //MG: Mahngebühren
    const DEBIT_TRANSACTION_TYPE_DEFAULT_INTEREST = 'VZ'; //VZ: Verzugszinsen
    const DEBIT_TRANSACTION_TYPE_SHIPPING_COSTS = 'VD'; //VD: Versandkosten
    const DEBIT_TRANSACTION_TYPE_PAYMENT_REQUEST = 'FD'; //FD: Forderung (default bei amount >0)
    const DEBIT_TRANSACTION_TYPE_CREDIT = 'GT'; //GT: Gutschrift (default bei amount <0)
    const DEBIT_TRANSACTION_TYPE_RETURNS = 'RT'; //RT: Retoure

    // PERSONAL DATA

    const PERSONAL_GENDER_MALE = 'm';
    const PERSONAL_GENDER_FEMALE = 'f';

    // CREDITCARD TYPE

    const CREDITCARD_TYPE_VISA = 'V';
    const CREDITCARD_TYPE_MASTERCARD = 'M';
    const CREDITCARD_TYPE_AMEX = 'A';
    const CREDITCARD_TYPE_DINERS = 'D';
    const CREDITCARD_TYPE_JCB = 'J';
    const CREDITCARD_TYPE_MAESTRO_INTERNATIONAL = 'O';
    const CREDITCARD_TYPE_MAESTRO_UK = 'U';
    const CREDITCARD_TYPE_DISCOVER = 'C';
    const CREDITCARD_TYPE_CARTE_BLEUE = 'B';

    // CONSUMER SCORE

    const CONSUMER_SCORE_GREEN = 'G';
    const CONSUMER_SCORE_YELLOW = 'Y';
    const CONSUMER_SCORE_RED = 'R';

    // CONSUMER SCORE TYPE

    const CONSUMER_SCORE_TYPE_INFOSCORE_HARD = 'IH';
    const CONSUMER_SCORE_TYPE_INFOSCORE_ALL = 'IA';
    const CONSUMER_SCORE_TYPE_INFOSCORE_ALL_BONI = 'IB';

    // CAPTURE MODE

    const CAPTURE_MODE_COMPLETED = 'completed';
    const CAPTURE_MODE_NOTCOMPLETED = 'notcompleted';

    // AVS RESULT

    const AVS_RESULT_A = 'A';
    const AVS_RESULT_F = 'F';
    const AVS_RESULT_N = 'N';
    const AVS_RESULT_U = 'U';
    const AVS_RESULT_Z = 'Z';

    // BANK ACCOUNT CHECK TYPE

    const BANK_ACCOUNT_CHECK_TYPE_REGULAR = '0';
    const BANK_ACCOUNT_CHECK_TYPE_POS_BLACKLIST = '1';

    // REMINDER LEVEL

    const REMINDER_LEVEL_LVL_1 = '1';
    const REMINDER_LEVEL_LVL_2 = '2';
    const REMINDER_LEVEL_LVL_3 = '3';
    const REMINDER_LEVEL_LVL_4 = '4';
    const REMINDER_LEVEL_LVL_5 = '5';
    const REMINDER_LEVEL_LVL_A = 'A';
    const REMINDER_LEVEL_LVL_S = 'S';
    const REMINDER_LEVEL_LVL_M = 'M';
    const REMINDER_LEVEL_LVL_I = 'I';

    // ADDRESS CHECK DIVERGENCE

    const ADDRESS_CHECK_DIVERGENCE_DEVIANT_SURNAME = 'L';
    const ADDRESS_CHECK_DIVERGENCE_DEVIANT_FIRSTNAME = 'F';
    const ADDRESS_CHECK_DIVERGENCE_DEVIANT_ADDRESS = 'A';
    const ADDRESS_CHECK_DIVERGENCE_DEVIANT_DATE_OF_BIRTH = 'B';

    // ADDRESS CHECK PERSONSTATUS

    const ADDRESS_CHECK_PERSONSTATUS_NONE = 'NONE'; //NONE: no verification of personal data carried out
    const ADDRESS_CHECK_PERSONSTATUS_PPB = 'PPB'; //PPB: first name & surname unknown
    const ADDRESS_CHECK_PERSONSTATUS_PHB = 'PHB'; //PHB: surname known
    const ADDRESS_CHECK_PERSONSTATUS_PAB = 'PAB'; //PAB: first name & surname unknown
    const ADDRESS_CHECK_PERSONSTATUS_PKI = 'PKI'; //PKI: ambiguity in name and address
    const ADDRESS_CHECK_PERSONSTATUS_PNZ = 'PNZ'; //PNZ: cannot be delivered (any longer)
    const ADDRESS_CHECK_PERSONSTATUS_PPV = 'PPV'; //PPV: person deceased
    const ADDRESS_CHECK_PERSONSTATUS_PPF = 'PPF'; //PPF: postal address details incorrect


    // ADDRESS CHECK SCORE

    const ADDRESS_CHECK_SCORE_GREEN = 'G';
    const ADDRESS_CHECK_SCORE_YELLOW = 'Y';
    const ADDRESS_CHECK_SCORE_RED = 'R';

    // ADDRESS CHECK SECSTATUS

    const ADDRESS_CHECK_SECSTATUS_CORRECT = '10';
    const ADDRESS_CHECK_SECSTATUS_CORRECTABLE = '20';
    const ADDRESS_CHECK_SECSTATUS_NONE_CORRECTABLE = '30';

    // ADDRESS CHECK TYPE

    const ADDRESS_CHECK_TYPE_NONE = 'NO';
    const ADDRESS_CHECK_TYPE_BASIC = 'BA';
    const ADDRESS_CHECK_TYPE_PERSON = 'PE';

}
