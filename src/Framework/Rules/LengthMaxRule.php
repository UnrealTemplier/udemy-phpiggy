<?php

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use http\Exception\InvalidArgumentException;

class LengthMaxRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        if (empty($params[0])) {
            throw new InvalidArgumentException("Maximum length not specified");
        }

        $length = (int)$params[0];
        return strlen($data[$field]) < $length;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "Maximum length cannot exceed {$params[0]} characters";
    }
}