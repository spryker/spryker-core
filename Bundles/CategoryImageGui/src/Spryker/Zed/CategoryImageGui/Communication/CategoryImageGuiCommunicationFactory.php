<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication;

use Spryker\Zed\CategoryImageGui\CategoryImageGuiDependencyProvider;
use Spryker\Zed\CategoryImageGui\Communication\Form\Transformer\ImageCollectionTransformer;
use Spryker\Zed\CategoryImageGui\Communication\Form\Transformer\ImageSetCollectionTransformer;
use Spryker\Zed\CategoryImageGui\Communication\Form\Transformer\LocaleTransformer;
use Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @method \Spryker\Zed\CategoryImageGui\CategoryImageGuiConfig getConfig()
 */
class CategoryImageGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createImageSetCollectionTransformer(): DataTransformerInterface
    {
        return new ImageSetCollectionTransformer(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createLocaleTransformer(): DataTransformerInterface
    {
        return new LocaleTransformer(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createImageCollectionTransformer(): DataTransformerInterface
    {
        return new ImageCollectionTransformer();
    }

    /**
     * @return \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface
     */
    public function getLocaleFacade(): CategoryImageGuiToLocaleInterface
    {
        return $this->getProvidedDependency(CategoryImageGuiDependencyProvider::FACADE_LOCALE);
    }
}
