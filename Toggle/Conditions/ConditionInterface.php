<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Context;

interface ConditionInterface
{
    public function __toString(): string;

    public function setContext(Context $context): static;

    public function validate($config, $argument = null): bool;

}
