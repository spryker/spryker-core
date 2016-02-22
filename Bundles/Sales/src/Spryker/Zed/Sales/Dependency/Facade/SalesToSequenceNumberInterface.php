<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;

interface SalesToSequenceNumberInterface
{

    /***
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettingsTransfer
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsTransfer $sequenceNumberSettingsTransfer);

}
