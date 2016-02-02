<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payone\ClientApi\Call;

use Spryker\Client\Payone\ClientApi\Request\CreditCardCheck as CreditCardCheckContainer;
use Spryker\Shared\Payone\PayoneApiConstants;

class CreditCardCheck extends AbstractCall
{

    /**
     * @var string
     */
    private $storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;

    /**
     * @void
     *
     * @return void
     */
    public function setDoStoreCardData()
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;
    }

    /**
     * @void
     *
     * @return void
     */
    public function setDoNotStoreCardData()
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_NO;
    }

    /**
     * @return \Spryker\Client\Payone\ClientApi\Request\CreditCardCheck
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
