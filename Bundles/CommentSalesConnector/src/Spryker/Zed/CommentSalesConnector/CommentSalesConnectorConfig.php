<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentSalesConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CommentSalesConnectorConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Comment\CommentConfig::COMMENT_TAG_ATTACHED
     */
    public const COMMENT_TAG_ATTACHED = 'attached';

    /**
     * @uses \Spryker\Zed\Comment\CommentConfig::COMMENT_THREAD_SALES_ORDER_OWNER_TYPE
     */
    public const COMMENT_THREAD_SALES_ORDER_OWNER_TYPE = 'sales_order';
}
