<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Session;

use SprykerFeature\Shared\Library\ConfigInterface;

interface SessionConfig extends ConfigInterface
{

    const SESSION_HANDLER_COUCHBASE = 'couchbase';
    const SESSION_HANDLER_REDIS = 'redis';
    const SESSION_HANDLER_MYSQL = 'mysql';

}
