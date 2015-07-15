<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Constraint;

use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Symfony\Component\Validator\Constraint;

class CodeExists extends Constraint
{

    public $message = 'The Code {{ value }} already exists in the Database!';

    /**
     * @var DiscountQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var
     */
    protected $voucherId;

    /**
     * @param DiscountQueryContainerInterface $queryContainer
     * @param string $voucherName
     * @param mixed $options
     */
    public function __construct(
        DiscountQueryContainerInterface $queryContainer,
        $voucherName = null,
        $options = null
    ) {
        $this->queryContainer = $queryContainer;
        $this->voucherId = $voucherName;
        parent::__construct($options);
    }

    /**
     * @return DiscountQueryContainerInterface
     */
    public function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @return int
     */
    public function getVoucherId()
    {
        return $this->voucherId;
    }

}
