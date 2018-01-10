<?php

namespace Edgar\EzFieldTypeExtra\Generator\Validator;

class FieldTypeValidator
{
    /**
     * @param string $fieldTypeName
     * @return string
     */
    public static function validateFieldTypeName(string $fieldTypeName): string
    {
        if (!preg_match('/^[a-zA-Z][ a-zA-Z]*$/', $fieldTypeName)) {
            throw new \InvalidArgumentException('The field type name contains invalid characters.');
        }

        return $fieldTypeName;
    }

    /**
     * @param string $fieldTypeNamespace
     * @return string
     */
    public static function validateFieldTypeNamespace(string $fieldTypeNamespace): string
    {
        if (!preg_match('/^[a-zA-Z]*$/', $fieldTypeNamespace)) {
            throw new \InvalidArgumentException('The field type namespace contains invalid characters.');
        }

        return $fieldTypeNamespace;
    }
}
