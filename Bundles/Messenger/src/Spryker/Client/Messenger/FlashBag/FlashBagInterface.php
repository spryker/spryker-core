<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger\FlashBag;

interface FlashBagInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message);

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message);

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message);
}
