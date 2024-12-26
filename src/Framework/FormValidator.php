<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;
use Framework\Exceptions\ValidationException;

class FormValidator
{
    private array $rules = [];

    public function addRule(string $alias, RuleInterface $rule)
    {
        $this->rules[$alias] = $rule;
    }

    public function validate(array $formData, array $fields)
    {
        $errors = [];

        foreach ($fields as $fieldName => $rules) {
            foreach ($rules as $ruleAlias) {
                $ruleParams = [];

                if (str_contains($ruleAlias, ":")) {
                    [$ruleAlias, $ruleParams] = explode(":", $ruleAlias);
                    $ruleParams = explode(",", $ruleParams);
                }

                $ruleValidator = $this->rules[$ruleAlias];

                if ($ruleValidator->validate($formData, $fieldName, $ruleParams)) {
                    continue;
                }

                $errors[$fieldName][] = $ruleValidator->getMessage($formData, $fieldName, $ruleParams);
            }
        }

        if (count($errors)) {
            throw new ValidationException($errors);
        }
    }
}
