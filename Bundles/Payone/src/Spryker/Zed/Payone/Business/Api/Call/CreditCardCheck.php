<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Call;

use Spryker\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer;
use Spryker\Shared\Payone\PayoneApiConstants;

class CreditCardCheck extends AbstractCall
{

    /**
     * @var string
     */
    private $storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;

    /**
     * @return void
     */
    public function setDoStoreCardData()
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;
    }

    /**
     * @return void
     */
    public function setDoNotStoreCardData()
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_NO;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer
     */
    public function mapCreditCardCheckData()
    {
        $container = new CreditCardCheckContainer();
        $this->applyStandardParameter($container);

        if ($container->getStoreCardData() === null) {
            $container->setStoreCardData($this->standardParameter->getStoreCardData());
        }

        $securityKey = $this->standardParameter->getKey();
        $hash = $this->hashGenerator->generateParamHash($container, $securityKey);

        $container->setHash($hash);

        return $container;
    }

}
