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

        foreach ($fields as $field => $rules) {
            foreach ($rules as $rule) {
                $ruleParams = [];

                if (str_contains($rule, ":")) {
                    [$rule, $ruleParams] = explode(":", $rule);
                    $ruleParams = explode(",", $ruleParams);
                }

                $ruleValidator = $this->rules[$rule];

                if ($ruleValidator->validate($formData, $field, $ruleParams)) {
                    continue;
                }

                $errors[$field][] = $ruleValidator->getMessage($formData, $field, $ruleParams);
            }
        }

        if (count($errors)) {
            throw new ValidationException($errors);
        }
    }
}
