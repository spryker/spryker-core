<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CommentMerchantRelationRequestConnectorConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Comment thread owner type for merchant relation request.
     *
     * @api
     *
     * @var string
     */
    public const COMMENT_THREAD_MERCHANT_RELATION_REQUEST_OWNER_TYPE = 'merchant_relation_request';
}
