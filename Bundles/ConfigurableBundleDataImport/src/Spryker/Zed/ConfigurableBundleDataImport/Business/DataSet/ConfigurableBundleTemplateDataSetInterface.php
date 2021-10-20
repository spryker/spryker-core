<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport\Business\DataSet;

interface ConfigurableBundleTemplateDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_KEY = 'configurable_bundle_template_key';

    /**
     * @var string
     */
    public const COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_UUID = 'configurable_bundle_template_uuid';

    /**
     * @var string
     */
    public const COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_NAME = 'configurable_bundle_template_name';

    /**
     * @var string
     */
    public const COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_IS_ACTIVE = 'configurable_bundle_template_is_active';
}
