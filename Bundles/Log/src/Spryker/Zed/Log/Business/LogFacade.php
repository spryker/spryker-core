<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Log\Business\LogBusinessFactory getFactory()
 */
class LogFacade extends AbstractFacade implements LogFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function clearLogs()
    {
        $this->getFactory()->createLogClearer()->clearLogs();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function startListener()
    {
        $this->getFactory()->createLogListener()->startListener();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function stopListener()
    {
        $this->getFactory()->createLogListener()->stopListener();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data)
    {
        return $this->getFactory()->createSanitizer()->sanitize($data);
    }
}
