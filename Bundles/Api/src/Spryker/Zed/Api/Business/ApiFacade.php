<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Api\Business\ApiBusinessFactory getFactory()
 */
class ApiFacade extends AbstractFacade implements ApiFacadeInterface
{

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroup($id)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->getGroupById($id);
    }

}
