<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Expander;

use Spryker\Zed\CategoryImageGui\Communication\Form\ImageSetCollectionForm;
use Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryImageFormExpander implements CategoryImageFormExpanderInterface
{
    protected const FIELD_IMAGE_SETS = 'imageSets';

    /**
     * @var \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Symfony\Component\Form\DataTransformerInterface
     */
    protected $imageSetCollectionTransformer;

    /**
     * @param \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface $localeFacade
     * @param \Symfony\Component\Form\DataTransformerInterface $imageSetCollectionTransformer
     */
    public function __construct(
        CategoryImageGuiToLocaleInterface $localeFacade,
        DataTransformerInterface $imageSetCollectionTransformer
    ) {
        $this->localeFacade = $localeFacade;
        $this->imageSetCollectionTransformer = $imageSetCollectionTransformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_IMAGE_SETS, ImageSetCollectionForm::class, [
            ImageSetCollectionForm::OPTION_LOCALES => $this->localeFacade->getAvailableLocales(),
        ]);

        $builder->get(static::FIELD_IMAGE_SETS)->addModelTransformer(
            $this->imageSetCollectionTransformer
        );
    }
}
