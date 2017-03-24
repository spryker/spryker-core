<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Oms\Helper\OmsDataBuilder;

use Codeception\Module;
use Generated\Shared\DataBuilder\ItemStateBuilder;
use Testify\Helper\BusinessHelper;

class OmsData extends Module
{

    public function haveState($override = [])
    {
        $omsFacade = $this->getLocator()->oms()->facade();
        $omsDataBuilder = new ItemStateBuilder($override);

        $omsFacade->
    }

    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface|\Generated\Zed\Ide\AutoCompletion|\Generated\Service\Ide\AutoCompletion
     */
    protected function getLocator()
    {
        return $this->getModule('\\' . BusinessHelper::class);
    }
}
