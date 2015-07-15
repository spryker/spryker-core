<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone\ClientApi\Call;

use SprykerFeature\Client\Payone\ClientApi\Request\CreditCardCheck as CreditCardCheckContainer;
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
        $securityKey = $this->standardParameter->getKey();
        $hash = $this->hashGenerator->generateHash($container, $securityKey);
        $container->setHash($hash);

        return $container;
    }

}
