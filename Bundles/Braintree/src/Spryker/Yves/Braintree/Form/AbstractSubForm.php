<?php

namespace Spryker\Yves\Braintree\Form;

use Generated\Shared\Transfer\BraintreePaymentTransfer;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Shared\Config\Config;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractSubForm extends AbstractSubFormType implements SubFormInterface
{

    const PAYMENT_PROVIDER = BraintreeConstants::BRAINTREE;
    const CLIENT_TOKEN = 'clientToken';

    /**
     * @var string
     */
    protected static $clientToken;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'data_class' => BraintreePaymentTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => []
        ]);
    }

    /**
     * Generate client token and store it in static class attribute to ensure
     * we do not invoke the API twice here for multiple sub forms.
     *
     * @return string
     */
    protected function generateClientToken()
    {
        if (static::$clientToken) {
            return static::$clientToken;
        }

        $environment = Config::get(BraintreeConstants::ENVIRONMENT);
        $merchantId = Config::get(BraintreeConstants::MERCHANT_ID);
        $publicKey = Config::get(BraintreeConstants::PUBLIC_KEY);
        $privateKey = Config::get(BraintreeConstants::PRIVATE_KEY);
        \Braintree\Configuration::environment($environment);
        \Braintree\Configuration::merchantId($merchantId);
        \Braintree\Configuration::publicKey($publicKey);
        \Braintree\Configuration::privateKey($privateKey);

        static::$clientToken = \Braintree\ClientToken::generate();
        
        return static::$clientToken;
    }

}
