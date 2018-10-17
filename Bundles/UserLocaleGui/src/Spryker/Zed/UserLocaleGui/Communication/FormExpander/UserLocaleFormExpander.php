<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocaleGui\Communication\FormExpander;

use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\UserLocaleGui\Communication\Mapper\LocaleMapperInterface;
use Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleBridgeInterface;
use Symfony\Component\Form\FormBuilderInterface;

class UserLocaleFormExpander implements UserLocaleFormExpanderInterface
{
    protected const FIELD_FK_LOCALE = 'fk_locale';
    protected const FIELD_FK_LOCALE_LABEL = 'Interface language';

    /**
     * @var \Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleBridgeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\UserLocaleGui\Communication\Mapper\LocaleMapperInterface
     */
    protected $localeMapper;

    /**
     * @param \Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleBridgeInterface $localeFacade
     * @param \Spryker\Zed\UserLocaleGui\Communication\Mapper\LocaleMapperInterface $localeMapper
     */
    public function __construct(UserLocaleGuiToLocaleBridgeInterface $localeFacade, LocaleMapperInterface $localeMapper)
    {
        $this->localeFacade = $localeFacade;
        $this->localeMapper = $localeMapper;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $this->addLocaleField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addLocaleField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_FK_LOCALE, SelectType::class, [
            'label' => static::FIELD_FK_LOCALE_LABEL,
            'choices' => $this->buildLocaleOptions(),
            'required' => false,
        ]);
    }

    /**
     * @return array
     */
    protected function buildLocaleOptions(): array
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        return $this->localeMapper->buildLocaleOptions($localeTransfers);
    }
}
