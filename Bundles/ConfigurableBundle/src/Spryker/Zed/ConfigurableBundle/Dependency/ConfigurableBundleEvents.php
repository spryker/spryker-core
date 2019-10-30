<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Dependency;

class ConfigurableBundleEvents
{
    /**
     * Specification:
     * - This event is used for configurable_bundle_template publishing.
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_TEMPLATE_PUBLISH = 'ConfigurableBundle.configurable_bundle_template.publish';

    /**
     * Specification:
     * - This event is used for configurable_bundle_template unpublishing.
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_TEMPLATE_UNPUBLISH = 'ConfigurableBundle.configurable_bundle_template.unpublish';

    /**
     * Specification:
     * - This event is used for spy_configurable_bundle_template entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_CREATE = 'Entity.spy_configurable_bundle_template.create';

    /**
     * Specification:
     * - This event is used for spy_configurable_bundle_template entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_UPDATE = 'Entity.spy_configurable_bundle_template.update';

    /**
     * Specification:
     * - This event is used for configurable_bundle_template_slot unpublishing.
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UNPUBLISH = 'ConfigurableBundle.spy_configurable_bundle_template_slot.unpublish';

    /**
     * Specification:
     * - This event is used for spy_configurable_bundle_template_slot entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_CREATE = 'Entity.spy_configurable_bundle_template_slot.create';

    /**
     * Specification:
     * - This event is used for spy_configurable_bundle_template_slot entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UPDATE = 'Entity.spy_configurable_bundle_template_slot.update';
}
