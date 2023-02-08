<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Expander;

use Symfony\Component\Form\FormBuilderInterface;

interface WarehouseUserAssignmentFormExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function expandForm(FormBuilderInterface $builder): void;
}
