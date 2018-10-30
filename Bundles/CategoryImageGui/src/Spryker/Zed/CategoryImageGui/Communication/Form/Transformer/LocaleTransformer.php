<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form\Transformer;

use Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface;
use Symfony\Component\Form\DataTransformerInterface;

class LocaleTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface $localeFacade
     */
    private $localeFacade;

    /**
     * LocaleTransformer constructor.
     * @param \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface $localeFacade
     */
    public function __construct(CategoryImageGuiToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $value
     *
     * @return string
     */
    public function transform($value)
    {
        if ($value !== null) {
            return $value->getIdLocale();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($value !== null) {
            return $this->localeFacade->getLocaleById($value);
        }
    }
}
