<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;

class FormValidator
{
    private array $rules = [];

    public function addRule(string $alias, RuleInterface $rule)
    {
        $this->rules[$alias] = $rule;
    }

    public function validate(array $formData, array $fields)
    {
        foreach ($fields as $fieldName => $rules) {
            foreach ($rules as $ruleAlias) {
                $ruleValidator = $this->rules[$ruleAlias];

                if ($ruleValidator->validate($formData, $fieldName, [])) {
                    continue;
                }

                echo "Error<br>";
            }
        }
    }
}
