<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\Plugin\Handler;

use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Symfony\Component\HttpFoundation\Request;

interface StepHandlerPluginWithMessengerInterface extends StepHandlerPluginInterface
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $dataTransfer);

    /**
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     *
     * @return $this
     */
    public function setFlashMessenger(FlashMessengerInterface $flashMessenger);

}
