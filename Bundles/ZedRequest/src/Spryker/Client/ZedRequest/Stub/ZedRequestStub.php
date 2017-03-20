<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Stub;

use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ZedRequestStub
{

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedStub
     */
    public function __construct(ZedRequestClientInterface $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getInfoMessages()
    {
        return $this->zedStub->getLastResponseInfoMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getSuccessMessages()
    {
        return $this->zedStub->getLastResponseSuccessMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getErrorMessages()
    {
        return $this->zedStub->getLastResponseErrorMessages();
    }

}
