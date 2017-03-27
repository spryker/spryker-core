<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Oms\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ItemStateBuilder;
use Testify\Helper\BusinessHelper;

class OmsData extends Module
{

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer
     */
    public function haveState($override = [])
    {
        $omsFacade = $this->getOmsFacade();
        $omsDataBuilder = new ItemStateBuilder($override);

        return $omsFacade->createItemState($omsDataBuilder->build());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function getOmsFacade()
    {
        return $this->getModule('\\' . BusinessHelper::class)->getLocator()->oms()->facade();
    }

}
