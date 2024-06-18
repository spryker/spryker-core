<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StoreContext\Business\Creator\StoreContextCreator;
use Spryker\Zed\StoreContext\Business\Creator\StoreContextCreatorInterface;
use Spryker\Zed\StoreContext\Business\Expander\StoreExpander;
use Spryker\Zed\StoreContext\Business\Expander\StoreExpanderInterface;
use Spryker\Zed\StoreContext\Business\Reader\StoreContextReader;
use Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface;
use Spryker\Zed\StoreContext\Business\Reader\TimezoneReader;
use Spryker\Zed\StoreContext\Business\Reader\TimezoneReaderInterface;
use Spryker\Zed\StoreContext\Business\Updater\StoreContextUpdater;
use Spryker\Zed\StoreContext\Business\Updater\StoreContextUpdaterInterface;
use Spryker\Zed\StoreContext\Business\Validator\Rule\ApplicationRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\ContextAlreadyExistRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\ContextNotFoundRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\DefaultContextExistRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\OneContextPerApplicationRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface;
use Spryker\Zed\StoreContext\Business\Validator\Rule\TimezoneRule;
use Spryker\Zed\StoreContext\Business\Validator\StoreContextValidator;
use Spryker\Zed\StoreContext\Business\Validator\StoreContextValidatorInterface;
use Spryker\Zed\StoreContext\Business\Writer\StoreContextWriter;
use Spryker\Zed\StoreContext\Business\Writer\StoreContextWriterInterface;

/**
 * @method \Spryker\Zed\StoreContext\StoreContextConfig getConfig()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextRepositoryInterface getRepository()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextEntityManagerInterface getEntityManager()
 */
class StoreContextBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\StoreContext\Business\Expander\StoreExpanderInterface
     */
    public function createStoreExpander(): StoreExpanderInterface
    {
        return new StoreExpander(
            $this->createStoreContextReader(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Writer\StoreContextWriterInterface
     */
    public function createStoreContextWriter(): StoreContextWriterInterface
    {
        return new StoreContextWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface
     */
    public function createStoreContextReader(): StoreContextReaderInterface
    {
        return new StoreContextReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Creator\StoreContextCreatorInterface
     */
    public function createStoreContextCreator(): StoreContextCreatorInterface
    {
        return new StoreContextCreator(
            $this->createStoreContextWriter(),
            $this->createStoreContextCreateValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Updater\StoreContextUpdaterInterface
     */
    public function createStoreContextUpdater(): StoreContextUpdaterInterface
    {
        return new StoreContextUpdater(
            $this->createStoreContextWriter(),
            $this->createStoreContextUpdateValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\StoreContextValidatorInterface
     */
    public function createStoreContextCreateValidator(): StoreContextValidatorInterface
    {
        return new StoreContextValidator($this->getCreateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\StoreContextValidatorInterface
     */
    public function createStoreContextUpdateValidator(): StoreContextValidatorInterface
    {
        return new StoreContextValidator($this->getUpdateValidatorRules());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\StoreContextValidatorInterface
     */
    public function createStoreContextValidator(): StoreContextValidatorInterface
    {
        return new StoreContextValidator($this->getDefaultValidatorRules());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Reader\TimezoneReaderInterface
     */
    public function createTimezoneReader(): TimezoneReaderInterface
    {
        return new TimezoneReader();
    }

    /**
     * @return array<\Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface>
     */
    public function getDefaultValidatorRules(): array
    {
        return [
            $this->createApplicationRule(),
            $this->createDefaultConfigurationRule(),
            $this->createOneContextPerApplicationRule(),
            $this->createTimezoneRule(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface>
     */
    public function getCreateValidatorRules(): array
    {
        return [
            $this->createContextAlreadyExistRule(),
            $this->createApplicationRule(),
            $this->createDefaultConfigurationRule(),
            $this->createOneContextPerApplicationRule(),
            $this->createTimezoneRule(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface>
     */
    public function getUpdateValidatorRules(): array
    {
        return [
            $this->createContextNotFoundRule(),
            $this->createApplicationRule(),
            $this->createDefaultConfigurationRule(),
            $this->createOneContextPerApplicationRule(),
            $this->createTimezoneRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    public function createTimezoneRule(): StoreContextValidatorRuleInterface
    {
        return new TimezoneRule($this->createTimezoneReader());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    public function createOneContextPerApplicationRule(): StoreContextValidatorRuleInterface
    {
        return new OneContextPerApplicationRule($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    public function createDefaultConfigurationRule(): StoreContextValidatorRuleInterface
    {
        return new DefaultContextExistRule();
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    public function createApplicationRule(): StoreContextValidatorRuleInterface
    {
        return new ApplicationRule($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    public function createContextAlreadyExistRule(): StoreContextValidatorRuleInterface
    {
        return new ContextAlreadyExistRule(
            $this->createStoreContextReader(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    public function createContextNotFoundRule(): StoreContextValidatorRuleInterface
    {
        return new ContextNotFoundRule(
            $this->createStoreContextReader(),
        );
    }
}
