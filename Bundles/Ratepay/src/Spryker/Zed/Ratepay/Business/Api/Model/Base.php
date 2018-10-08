<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Builder\Head;

abstract class Base extends AbstractRequest
{
    public const ROOT_TAG = 'request';

    public const OPERATION = '';

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Builder\Head
     */
    protected $head;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Builder\Head $head
     */
    public function __construct(Head $head)
    {
        $this->head = $head;
    }

    /**
     * @return array
     */
    protected function buildData()
    {
        $this->getHead()->setOperation(static::OPERATION);
        return [
            '@version' => RatepayConstants::RATEPAY_REQUEST_VERSION,
            '@xmlns' => RatepayConstants::RATEPAY_REQUEST_XMLNS_URN,
            $this->getHead()->getRootTag() => $this->getHead(),
        ];
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Builder\Head $head
     *
     * @return $this
     */
    public function setHead(Head $head)
    {
        $this->head = $head;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\Head
     */
    public function getHead()
    {
        return $this->head;
    }
}
