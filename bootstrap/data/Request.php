<?php

class Request
{
    private static $model = null;
    protected static $messages = [];


    public static function validating($rules, $model = null)
    {
        if (!is_null($model)) self::$model = $model;

        $fields = self::getFields();
        $is_form_valid = true;

        foreach ($rules as $key => $value) {
            $values = explode('||', $value);
            $has_args = false;
            $is_rule_valid = true;

            foreach ($values as $rule) {
                if (strpos($rule, ':') !== false) {
                    $args = substr($rule, strpos($rule, ':') + 1);
                    $param = substr($rule, 0, strpos($rule, ':'));
                    $has_args = true;
                } else
                    $param = $rule;

                if ($has_args)
                    $is_rule_valid = self::$param($key, $fields[$key], $args);
                else
                    $is_rule_valid = self::$param($key, $fields[$key]);

                if (!$is_rule_valid) {
                    $is_form_valid = false;
                    break;
                }
            }
        }

        if (!$is_form_valid) {
            redirect($_SERVER['HTTP_REFERER']);
            return false;
        }

        return $fields;
    }

    private static function getFields()
    {
        $fields = [];

        if (isset($_POST)) {
            Session::setOlds($_POST);
            foreach ($_POST as $key => $value) {
                $fields[$key] = $value;
            }
        }

        if (isset($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $fields[$key] = $value;
            }
        }

        return $fields;
    }


    private static function required($field, $value)
    {
        if (empty(trim($value))) {
            Session::setErrors([$field => self::generateMessage($field, 'required', "El campo es requerido")]);
            return false;
        }
        return true;
    }

    private static function min($field, $value, $min)
    {
        if (strlen(trim($value)) < $min) {
            Session::setErrors([$field => self::generateMessage($field, 'min', "El campo debe tener más de " . $min . " caracteres")]);
            return false;
        }
        return true;
    }

    private static function max($field, $value, $max)
    {
        if (strlen(trim($value)) > $max) {
            Session::setErrors([$field => self::generateMessage($field, 'max', "El campo debe tener menos de " . $max . " caracteres")]);
            return false;
        }
        return true;
    }

    private static function pattern($field, $value, $pattern)
    {
        if (!preg_match($pattern, $value)) {
            Session::setErrors([$field => self::generateMessage($field, 'pattern', "El campo no cumple con el formato")]);
            return false;
        }
        return true;
    }

    private static function unique($field, $value, $args)
    {
        $args_list = explode(',', $args);
        $query = "SELECT id FROM $args_list[0] WHERE $args_list[1] = '$value'";
        if (isset($args_list[2])) {
            if ($args_list[2] === 'id' && isset(self::$model->id))
                $query .= " AND id != " . self::$model->id;
        }

        Model::$query = $query;
        Model::get_query();
        if (count(Model::$rows) > 0) {
            Session::setErrors([$field => self::generateMessage($field, 'unique', "El valor de este campo ya está registrado")]);
            return false;
        }
        return true;
    }

    private static function file_required($field, $file)
    {
        if (empty($file['name'])) {
            Session::setErrors([$field => self::generateMessage($field, 'file_required', "El archivo es requerido")]);
            return false;
        }
        return true;
    }

    private static function file_max($field, $file, $max)
    {
        if ($file['size'] > $max * 1024) {
            $msg = ($max / 1024 >= 1) ? round($max / 1024 * 100) / 100 . " MB" : $max . " KB";
            return false;
            Session::setErrors([$field => self::generateMessage($field, 'file_max', "El archivo debe pesar menos de " . $msg)]);
        }
        return true;
    }

    private static function file_mime($field, $file, $mimes)
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mimes = explode(',', $mimes);
        $has_match = false;

        foreach ($mimes as $mime) {
            if ($extension === $mime)
                $has_match = true;
        }

        if (empty($extension)) $has_match = true;

        if (!$has_match) {
            Session::setErrors([$field => self::generateMessage($field, 'file_mime', "El tipo de archivo es incorrecto (correctos: " . implode(',', $mimes) . ")")]);
            return false;
        }
        return true;
    }


    private static function generateMessage($field, $type, $message_default)
    {
        $name = "$field.$type";
        $message = "";

        foreach (self::$messages as $key => $value) {
            if ($name === $key)
                $message = $value;
        }

        if (empty($message)) $message = $message_default;

        return $message;
    }
}
