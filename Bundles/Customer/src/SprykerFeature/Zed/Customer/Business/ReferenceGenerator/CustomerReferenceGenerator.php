<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\ReferenceGenerator;

use Generated\Shared\Customer\CustomerInterface;

class CustomerReferenceGenerator implements CustomerReferenceGeneratorInterface
{

    const SEQUENCE_NAME = 'OrderReferenceGenerator';

    const PREFIX_STAGING = 'S';
    const PREFIX_DEVELOPMENT = 'D';
    const PREFIX_PRODUCTION = 'P';

    /**
     * @var CustomerSequenceInterface
     */
    protected $customerSequence;

    /**
     * @var bool
     */
    protected $isDevelopment;

    /**
     * @var bool
     */
    protected $isStaging;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * @param CustomerSequenceInterface $customerSequence
     * @param bool $isDevelopment
     * @param bool $isStaging
     * @param string $storeName
     */
    public function __construct(
        CustomerSequenceInterface $customerSequence,
        $isDevelopment,
        $isStaging,
        $storeName
    ) {
        $this->customerSequence = $customerSequence;
        $this->isDevelopment = $isDevelopment;
        $this->isStaging = $isStaging;
        $this->storeName = $storeName;
    }

    /**
     * @param CustomerInterface $orderTransfer
     *
     * @return string
     */
    public function generateCustomerReference(CustomerInterface $orderTransfer)
    {
        $customerReferenceParts = [
            $this->getEnvironmentPrefix(),
            $this->storeName,
        ];

        if ($this->isDevelopment) {
            $customerReferenceParts[] = $this->getTimestamp();
        } else {
            $customerReferenceParts[] = $this->customerSequence->generate();
        }

        return implode('-', $customerReferenceParts);
    }

    /**
     * @return string
     */
    protected function getEnvironmentPrefix()
    {
        if ($this->isStaging) {
            return self::PREFIX_STAGING;
        }

        if ($this->isDevelopment) {
            return self::PREFIX_DEVELOPMENT;
        }

        return self::PREFIX_PRODUCTION;
    }

    /**
     * @return string
     */
    protected function getTimestamp()
    {
        $ts = strtr(microtime(), [
            '.' => '',
            ' ' => '',
        ]);

        return $ts;
    }
}
