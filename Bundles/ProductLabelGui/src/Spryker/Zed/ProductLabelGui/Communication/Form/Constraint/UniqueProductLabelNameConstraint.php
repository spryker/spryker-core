<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class UniqueProductLabelNameConstraint extends Constraint
{

    const OPTION_QUERY_CONTAINER = 'queryContainer';

    /**
     * @var \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    public function findProductLabelByName($name)
    {
        return $this->queryContainer->queryProductLabelByName($name)->findOne();
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getMessage($name)
    {
        return sprintf('A product label with name "%s" already exists', $name);
    }

}
