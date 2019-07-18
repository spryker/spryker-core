<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsContentWidget\Dependency;

interface CmsContentWidgetConfigurationProviderInterface
{
    public const DEFAULT_TEMPLATE_IDENTIFIER = 'default';

    /**
     * @return string
     */
    public function getFunctionName();

    /**
     * @return array
     */
    public function getAvailableTemplates();

    /**
     * @return string
     */
    public function getUsageInformation();
}
