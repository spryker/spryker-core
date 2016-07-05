<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\FactFinder\Business\Api\Builder;

class Head extends AbstractBuilder implements BuilderInterface
{

    const ROOT_TAG = 'head';

    /**
     * @return array
     */
    public function buildData()
    {
        $return = [
            'system-id' => $this->requestTransfer->getHead()->getSystemId(),
            'transaction-id' => $this->requestTransfer->getHead()->getTransactionId(),
        ];
        
        return $return;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

}
