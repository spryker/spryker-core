<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

interface ApiFacadeInterface
{

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroup($id);

}
