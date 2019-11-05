<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodesRestApi\Zed;

use Generated\Shared\Transfer\AddCandidateRequestTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\RemoveCodeRequestTransfer;

interface CartCodesRestApiStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddCandidateRequestTransfer $addCandidateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(AddCandidateRequestTransfer $addCandidateRequestTransfer): CartCodeOperationResultTransfer;

    /**
     * @param \Generated\Shared\Transfer\RemoveCodeRequestTransfer $removeCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCode(RemoveCodeRequestTransfer $removeCodeRequestTransfer): CartCodeOperationResultTransfer;
}
