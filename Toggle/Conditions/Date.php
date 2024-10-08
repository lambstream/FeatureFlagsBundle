<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

use DateTime;

class Date extends AbstractCondition implements ConditionInterface
{
    /**
     * Current date, in DateTime format.
     *
     * @see http://php.net/manual/en/class.datetime.php
     *
     * @var string
     */
    protected $currentDate = "now";

    /**
     * @param mixed $config
     * @param null  $argument
     * @return bool
     */
    public function validate($config, $argument = null): bool
    {
        $currentDate = new DateTime($this->currentDate);
        $startDate = !empty($config['start_date']) ? new DateTime($config['start_date']) : $currentDate;
        $endDate   = !empty($config['end_date'])   ? new DateTime($config['end_date'])   : $currentDate;
        return ($startDate <= $currentDate && $currentDate <= $endDate);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'date';
    }
}
