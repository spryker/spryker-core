<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Dependency;

interface CmsSlotEvents
{
    /**
     * Specification
     * - This events will be used for CmsSlot publishing.
     *
     * @api
     */
    public const CMS_SLOT_PUBLISH = 'CmsSlot.slot.publish';

    /**
     * Specification
     * - This events will be used for spy_cms_slot entity update.
     *
     * @api
     */
    public const ENTITY_SPY_CMS_SLOT_UPDATE = 'Entity.spy_cms_slot.update';

    /**
     * Specification:
     * - Represents spy_cms_slot entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_CMS_SLOT_CREATE = 'Entity.spy_cms_slot.create';
}
