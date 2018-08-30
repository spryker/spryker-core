<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Tabs;

class ProductConcreteFormAddTabs extends ProductConcreteFormEditTabs
{
    protected const TEMPLATE_TAB_GENERAL = '@ProductManagement/Product/_partials/AddVariant/tab-general.twig';

    public function __construct()
    {
        parent::__construct([]);
    }
}
