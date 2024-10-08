<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

class IpAddress extends AbstractCondition implements ConditionInterface
{

    /**
     * @param mixed $config
     * @param null  $argument
     * @return bool
     */
    public function validate($config, $argument = null): bool
    {
        return in_array(
            $this->context->get('client_ip'),
            $config
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'ip_address';
    }

}
