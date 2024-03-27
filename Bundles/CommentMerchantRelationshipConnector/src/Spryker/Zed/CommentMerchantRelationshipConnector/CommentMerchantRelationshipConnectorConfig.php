<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationshipConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CommentMerchantRelationshipConnectorConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Comment thread owner type for merchant relationship.
     *
     * @api
     *
     * @var string
     */
    public const COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE = 'merchant_relationship';
}
