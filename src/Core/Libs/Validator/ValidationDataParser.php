<?php

namespace Core\Libs\Validator;

use Illuminate\Support\Str;

trait ValidationDataParser
{
    protected $rule;

    protected $arg;

    protected function createRulesDataArray($field, $label = '', $value=null, $rules='')
    {
        return [
            'field' => $field,
            'label' => $label,
            'value' => $value,
            'rules' => $rules,
        ];
    }

    protected function parseCustomMsgFromArray($attribute, array $rules)
    {
        $arrayWithmsg = [];

        foreach ($rules as $rule => $msg) {
            $parsedrule = $this->parseRules($rule);

            $arrayWithmsg[$parsedrule['rule']] = $msg;

        }

        return $arrayWithmsg;
    }

    protected function parseRules($rules)
    {
        if(strpos($rules, ':')!==false){
            list($rule, $arg) = explode(':', $rules);

            $parsed['rule'] = $rule;
            $parsed['arg'] = $arg;

        } else {
            $parsed['rule'] = $rules;
            $parsed['arg'] = null;
        }
        return $parsed;
    }

    /**
     * Parse the data array, converting dots to ->.
     *
     * @param  array  $data
     * @return array
     */
    public function parseData(array $data)
    {
        $newData = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->parseData($value);
            }

            // If the data key contains a dot, we will replace it with another character
            // sequence so it doesn't interfere with dot processing when working with
            // array based validation rules and array_dot later in the validations.
            if (Str::contains($key, '.')) {
                $newData[str_replace('.', '->', $key)] = $value;
            } else {
                $newData[$key] = $value;
            }
        }

        return $newData;
    }
}
