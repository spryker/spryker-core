<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Lumberjack\Code\Log;

class Types
{

    const REQUEST = 'request';
    // subtype get/post/put/delete oder cli

    const TRANSFER = 'transfer';
    const TRANSFER_REQUEST = 'request';
    const TRANSFER_RESPONSE = 'response';

    const CRONJOB = 'cronjob';
    // subtype name

    const SAVE = 'save'; // db
    // subtype entity or table name

    const EXCEPTION = 'exception';
    const EXCEPTION_FATAL = 'fatal';
    // subtype error, fatal, warning, exception

    const STATEMACHINE = 'statemachine';
    // subtype TBD

    const EXTERNAL = 'external';
    // subtype name of external interface

    const YVES_REQUEST = 'request';

    const ZED_REQUEST = 'request';

    const PAYMENT = 'payment';

    const FULFILLMENT = 'fulfillment';
    // subtype provider name

    const MAIL = 'mail';
    // subtype adapter name


}
