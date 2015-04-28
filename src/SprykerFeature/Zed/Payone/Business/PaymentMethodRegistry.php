<?php

namespace SprykerFeature\Zed\Payone\Business;


class PaymentMethodRegistry implements PaymentMethodRegistryInterface
{

    /**
     * @var PaymentMethodMapperInterface[]
     */
    protected $registeredMappers;


    /**
     * @param PaymentMethodMapperInterface $paymentMethodMapper
     */
    public function registerPaymentMethodMapper(PaymentMethodMapperInterface $paymentMethodMapper)
    {
        $this->registeredMappers[$paymentMethodMapper->getName()] = $paymentMethodMapper;
    }

    /**
     * @param string $name
     * @return null|PaymentMethodMapperInterface
     */
    public function findPaymentMethodMapperByName($name)
    {
        if (array_key_exists($name, $this->registeredMappers)) {
            return $this->registeredMappers[$name];
        }

        return null;
    }

}