<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SalesReturnSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SalesReturnSearchConfig extends AbstractBundleConfig
{
    /**
     * Defines resource name, that will be used for key generation.
     */
    public const RETURN_REASON_RESOURCE_NAME = 'return_reason';

    /**
     * Defines queue name as used for processing translation messages.
     */
    public const SYNC_SEARCH_RETURN = 'sync.search.return';

    /**
     * Defines queue name as used for processing translation error messages.
     */
    public const SYNC_SEARCH_RETURN_ERROR = 'sync.search.return.error';

    /**
     * This events that will be used for key writing.
     */
    public const RETURN_REASON_PUBLISH_WRITE = 'Return.reason.publish_write';

    /**
     * This events that will be used for key deleting.
     */
    public const RETURN_REASON_PUBLISH_DELETE = 'Return.reason.publish_delete';

    /**
     * This events will be used for spy_sales_return_reason entity creation.
     */
    public const ENTITY_SPY_SALES_RETURN_REASON_CREATE = 'Entity.spy_sales_return_reason.create';

    /**
     * This events will be used for spy_sales_return_reason entity changes.
     */
    public const ENTITY_SPY_SALES_RETURN_REASON_UPDATE = 'Entity.spy_sales_return_reason.update';

    /**
     * This events will be used for spy_sales_return_reason entity deletion.
     */
    public const ENTITY_SPY_SALES_RETURN_REASON_DELETE = 'Entity.spy_sales_return_reason.delete';
}
