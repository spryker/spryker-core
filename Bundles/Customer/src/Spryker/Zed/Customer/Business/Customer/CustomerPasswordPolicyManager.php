<?php


namespace Spryker\Zed\Customer\Business\Customer;


use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPasswordPolicyPluginInterface;

class CustomerPasswordPolicyManager implements CustomerPasswordPolicyManagerInterface
{

    /**
     * @var CustomerPasswordPolicyPluginInterface[]
     */
    protected $policyPlugins = [];

    /**
     * @param CustomerPasswordPolicyPluginInterface[] $policyPlugins
     */
    public function __cunstruct(array $policyPlugins)
    {
        $this->policyPlugins = $policyPlugins;
    }

    public function check(): CustomerPasswordPolicyResultTransfer
    {
        $result = new CustomerPasswordPolicyResultTransfer();

        foreach ($this->policyPlugins as $plugin) {
            $policyName = $plugin->getName();
        }

        return $result;
    }

}
