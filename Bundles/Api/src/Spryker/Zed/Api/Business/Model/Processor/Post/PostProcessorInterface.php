<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Post;

use Generated\Shared\Transfer\ApiResponseTransfer;

interface PostProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return void
     */
    public function process(ApiResponseTransfer $apiResponseTransfer);

}
