<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;

class OrderReferenceGenerator implements OrderReferenceGeneratorInterface
{

    const SEQUENCE_NAME = 'OrderReferenceGenerator';

    /** @var OrderSequenceInterface */
    protected $orderSequence;

    /** @var bool */
    protected $isDevelopment;

    /** @var bool */
    protected $isStaging;

    /** @var string */
    protected $storeName;

    /**
     * @param OrderSequenceInterface $orderSequence
     * @param bool $isDevelopment
     * @param bool $isStaging
     * @param string $storeName
     */
    public function __construct(
        OrderSequenceInterface $orderSequence,
        $isDevelopment,
        $isStaging,
        $storeName
    ) {
        $this->orderSequence = $orderSequence;
        $this->isDevelopment = $isDevelopment;
        $this->isStaging = $isStaging;
        $this->storeName = $storeName;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function generateOrderReference(OrderTransfer $orderTransfer)
    {
        $orderReferenceParts = [
            $this->getEnvironmentPrefix(),
            $this->storeName,
        ];

        if ($this->isDevelopment) {
            $orderReferenceParts[] = $this->getTimestamp();
        } else {
            $orderReferenceParts[] = $this->orderSequence->generate();
        }

        return implode('-', $orderReferenceParts);
    }

    /**
     * @return string
     */
    protected function getEnvironmentPrefix()
    {
        if ($this->isStaging) {
            return 'S';
        }

        if ($this->isDevelopment) {
            return 'D';
        }

        return 'P';
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
