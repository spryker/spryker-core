<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetBuilderPluginInterface;

abstract class AbstractWidgetBuilderPlugin extends AbstractPlugin implements WidgetBuilderPluginInterface
{

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getApplication()
    {
        // TODO: application is set in project level: Pyz/Yves/Application/Plugin/Provider/ApplicationServiceProvider.php:79 !!!
        return (new Pimple())->getApplication();
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        return $this->getApplication()['locale'];
    }

}
