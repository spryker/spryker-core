<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Messenger\FlashMessenger;

interface FlashMessengerInterface
{
    /**
     * @param string $message
     *
     * @throws \ErrorException
     *
     * @return $this
     */
    public function addSuccessMessage($message);

    /**
     * @param string $message
     *
     * @throws \ErrorException
     *
     * @return $this
     */
    public function addInfoMessage($message);

    /**
     * @param string $message
     *
     * @throws \ErrorException
     *
     * @return $this
     */
    public function addErrorMessage($message);
}
