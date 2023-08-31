<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter;

/**
 * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatter} instead.
 */
class CustomHttpHeaderFormatter extends HttpHeaderFormatter
{
    /**
     * @var string
     */
    protected const HEADER_NAME_PREFIX = 'X-';
}
