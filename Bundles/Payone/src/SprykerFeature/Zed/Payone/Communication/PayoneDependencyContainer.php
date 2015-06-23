<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Payone\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\PayoneCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Payone\PayoneDependencyProvider;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use Symfony\Component\Validator\Validator;

/**
 * @method PayoneCommunication getFactory()
 * @method PayoneQueryContainerInterface getQueryContainer()
 */
class PayoneDependencyContainer extends AbstractDependencyContainer
{

}
