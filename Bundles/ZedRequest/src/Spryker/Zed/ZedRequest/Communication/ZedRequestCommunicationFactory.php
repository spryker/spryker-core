<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Messenger\Business\MessengerFacade;

class ZedRequestCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * JUST TO MAKE IT WORK
     *
     * @return \Spryker\Zed\Messenger\Business\MessengerFacade
     */
    public function getMessengerFacade()
    {
        return new MessengerFacade();
    }

}
