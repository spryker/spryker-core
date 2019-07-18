<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Oms;

class OmsConfig
{
    /**
     * Predefined enumerated state flag list as defined in oms.xsd file.
     */
    public const STATE_TYPE_FLAG_EXCLUDE_FROM_CUSTOMER = 'exclude from customer';
    public const STATE_TYPE_FLAG_EXCLUDE_FROM_INVOICE = 'exclude from invoice';
    public const STATE_TYPE_FLAG_READY_FOR_INVOICE = 'ready for invoice';
    public const STATE_TYPE_FLAG_WAITING_FOR_EXPORT = 'waiting for export';
}
