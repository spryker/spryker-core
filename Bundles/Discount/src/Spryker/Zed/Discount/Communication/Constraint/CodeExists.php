<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Constraint;

use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Symfony\Component\Validator\Constraint;

class CodeExists extends Constraint
{

    public $message = 'The Code {{ value }} already exists in the Database!';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var
     */
    protected $voucherId;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $queryContainer
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
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
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
