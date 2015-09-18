<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Call;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer;
use SprykerFeature\Shared\Payone\PayoneApiConstants;

class CreditCardCheck extends AbstractCall
{

    /**
     * @var string
     */
    private $storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;

    /**
     * @void
     */
    public function setDoStoreCardData()
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;
    }

    /**
     * @void
     */
    public function setDoNotStoreCardData()
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_NO;
    }

    /**
     * @return CreditCardCheckContainer
     */
    public function mapCreditCardCheckData()
    {
        $container = new CreditCardCheckContainer();
        $this->applyStandardParameter($container);

        if (null === $container->getStoreCardData()) {
            $container->setStoreCardData($this->standardParameter->getStoreCardData());
        }

        $securityKey = $this->standardParameter->getKey();
        $hash = $this->hashGenerator->generateParamHash($container, $securityKey);

        $container->setHash($hash);

        return $container;
    }

}
