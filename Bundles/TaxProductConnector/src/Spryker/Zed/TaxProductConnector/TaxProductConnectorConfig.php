<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class TaxProductConnectorConfig extends AbstractBundleConfig
{
    public const EXCEPTION_MESSAGE_TAX_SET_NOT_FOUND_FOR_ABSTRACT = 'Could not get tax set, product abstract with id "%d" not found.';
}
