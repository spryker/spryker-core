<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Provider;

interface ProductConcreteRelationTablesProviderInterface
{
    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable[]
     */
    public function getTables(): array;
}
