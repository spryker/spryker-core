<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SprykGui\Communication\Form\DataProvider\SprykDataProvider;
use Spryker\Zed\SprykGui\Communication\Form\SprykSelectForm;

class SprykGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSprykSelectForm()
    {
        return $this->getFormFactory()->create(
            SprykSelectForm::class,
            $this->createSprykFormDataProvider()->getData(),
            $this->createSprykFormDataProvider()->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\SprykGui\Communication\Form\DataProvider\SprykDataProvider
     */
    public function createSprykFormDataProvider()
    {
        return new SprykDataProvider();
    }
}
