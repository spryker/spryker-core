<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Attribute;

use Spryker\Zed\ProductManagement\Communication\Form\AttributeForm;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReadOnlyAttributeForm extends AttributeForm
{

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'disabled' => true,
        ]);
    }

}
