<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Plugin\PersistentCartShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;

class PreviewCartShareOptionPlugin extends AbstractPlugin implements CartShareOptionPluginInterface
{
    /**
     * @uses \Spryker\Shared\PersistentCartShare\PersistentCartShareConfig::SHARE_OPTION_KEY_PREVIEW
     */
    protected const SHARE_OPTION_KEY_PREVIEW = 'PREVIEW';
    protected const SHARE_OPTION_GROUP_EXTERNAL = 'external';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getShareOptionKey(): string
    {
        return static::SHARE_OPTION_KEY_PREVIEW;
    }

    /**
     * {@inheritdoc}
     * - Returns true, since preview share option is available for all customers by default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    public function isApplicable(?CustomerTransfer $customerTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getShareOptionGroup(): string
    {
        return static::SHARE_OPTION_GROUP_EXTERNAL;
    }
}
