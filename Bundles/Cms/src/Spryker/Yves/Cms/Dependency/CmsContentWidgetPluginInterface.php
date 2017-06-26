<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cms\Dependency;

interface CmsContentWidgetPluginInterface
{

    /**
     * Specification:
     *  - Return callable function, that could be any callable which is accepted by Twig_SimpleFunction first parameter.
     *  - This functions will be injected into Yves twig environment and can be used independently from cms.
     *
     * @api
     *
     * @return \Callable
     */
    public function getContentWidgetFunction();

}
