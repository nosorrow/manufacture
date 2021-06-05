<?php

namespace Core\Libs\Validator\Rules;

use Core\Libs\Database\MySqlPDOConnection;

trait ValidationRules
{
    public $validatedFilename;

    /**
     * Alpha
     *
     * @param string
     * @return    bool
     */
    public function alpha($str): bool
	{
        return ctype_alpha($str);
    }

    /**
     * alpha_numeric
     *
     * @param $str
     * @return bool
     */
    public function alpha_num($str): bool
	{
        return ctype_alnum((string)$str);
    }

    /**
     * Alias of alpha_numeric
     *
     * @param $str
     * @return bool
     */
    public function alnum($str): bool
	{
        return ctype_alnum((string)$str);
    }

    /**
     * Alpha-numeric with underscores and dashes
     *
     * @param string
     * @return    bool
     */
    public function alpha_dash($str): bool
	{
        return (bool)preg_match('#^[a-z0-9_-]+$#i', $str);
    }

    /**
     * @param $str
     * @param $date
     * @return bool
     */
    public function after($str, $date): bool
	{
        if ($this->date($date) === false) {

            if (!empty($this->getValue($date))) {
                return strtotime($str) > strtotime($this->getValue($date));

            }

			return false;

		}

		$date = strtotime($date);

		$str = strtotime($str);

		return $str > $date;

	}

    /**
     * valid_date
     *
     * @param $date
     * @return bool
     */
    public function date($date): bool
	{
        if (false === strtotime($date)) {
            return false;
        }
        return true;
    }

    protected function getValue($field)
    {
        return $this->parsed_rule_data[$field]['value'];
    }

    /**
     * @param $str
     * @param $date
     * @return bool
     */
    public function before($str, $date): bool
	{
        if ($this->date($date) === false) {

            if (!empty($this->getValue($date))) {

                return (bool)(strtotime($str) < strtotime($this->getValue($date)));

            } else {

                return false;
            }

        } else {

            $date = strtotime($date);

            $str = strtotime($str);

            return (bool)($str < $date);
        }
    }

    public function date_format($date, $format): bool
	{
        $date_format = \DateTime::createFromFormat($format, $date);
        $errors = \DateTime::getLastErrors();

        return (bool)($errors['warning_count'] + $errors['error_count']) == 0;
    }

    /**
     * differs
     *
     * @param $str
     * @param $field
     * @return bool
     */
    public function different($str, $field): bool
	{
        return $str !== $this->getValue($field) ? true : false;

    }

    /**
     * email
     *
     * @param $str
     * @return bool
     */
    public function email($str): bool
	{
        /*if (function_exists('idn_to_ascii') && $atpos = strpos($str, '@')) {
            $str = substr($str, 0, ++$atpos) . idn_to_ascii(substr($str, $atpos));
        }*/

        if (function_exists('idn_to_ascii') && preg_match('#\A([^@]+)@(.+)\z#', $str, $matches)) {
            $domain = is_php('5.4')
                ? idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46)
                : idn_to_ascii($matches[2]);
            $str = $matches[1] . '@' . $domain;
        }
        return (bool)filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Exact Length
     *
     * @param string
     * @param string
     * @return    bool
     */
    public function exact($str, $val): bool
	{
        if (!is_numeric($val)) {
            return FALSE;
        }

        return (mb_strlen($str) === (int)$val);
    }

    /**
     * Greater
     *
     * @param string
     * @param int
     * @return    bool
     */
    public function greater($str, $val): bool
	{
        return is_numeric($str) ? ($str > $val) : false;
    }

    /**
     * The field under validation must be greater than the given field.
     * @param $str
     * @param $field
     * @return bool
     */
    public function gt($str, $field): bool
	{
        return is_numeric($str) ? (bool)((int)$str > (int)$this->getValue($field)) : false;
    }

    /**
     * The field under validation must be greater or equal than the given field.
     * @param $str
     * @param $field
     * @return bool
     */
    public function gte($str, $field): bool
	{
        return is_numeric($str) ? (int)$str >= (int)$this->getValue($field) : false;
    }

    /**
     * greater_equal
     *
     * @param string
     * @param int
     * @return    bool
     */
    public function greater_equal($str, $val): bool
	{
        return is_numeric($str) ? ($str >= $val) : false;
    }

    /**
     * Value should be within an array of values
     * ['in:5,6,8']
     *
     * @param string
     * @param string
     * @return    bool
     */
    public function in($value, $list): bool
	{
        return in_array($value, explode(',', $list), true);
    }

    /**
     * Integer
     *
     * @param string
     * @return    bool
     */
    public function integer($str): bool
	{
        return (bool)preg_match('#^[\-+]?[0-9]+$#', $str);
    }

    /**
     * is_numeric
     *
     * @param $val
     * @return bool
     */
    public function is_numeric($val): bool
	{
        return is_numeric($val);
    }

    /**
     * less
     *
     * @param $str
     * @param $val
     * @return bool
     */
    public function less($str, $val): bool
	{
        return is_numeric($str) ? (bool)($str < $val) : false;
    }

    /**
     * The field under validation must be less than the given field.
     * @param $str
     * @param $field
     * @return bool
     */
    public function lt($str, $field): bool
	{
        return is_numeric($str) ? (bool)($str < (int)$this->getValue($field)) : false;
    }

    /**
     * less_equal
     *
     * @param $str
     * @param $val
     * @return bool
     */
    public function less_equal($str, $val): bool
	{
        return is_numeric($str) ? (bool)($str <= $val) : false;
    }

    /**
     * The field under validation must be less  or equal than the given field.
     * @param $str
     * @param $field
     * @return bool
     */
    public function lte($str, $field): bool
	{
        return is_numeric($str) ? (bool)($str <= $this->getValue($field)) : false;
    }

    /**
     * max
     * @param $str
     * @param $max
     * @return bool
     */
    public function max($str, $max): bool
	{
        return (mb_strlen($str) <= $max);
    }

    /**
     * min
     *
     * @param $str
     * @param $min
     * @return bool
     */
    public function min($str, $min): bool
	{
        return (mb_strlen($str) >= $min);
    }

    /**
     * само букви и интервал
     * @param $str
     * @return bool
     */
    public function name($str): bool
	{
        return (bool)(preg_match('#^[a-zа-я\s]+$#iu', $str));
    }

	/**
	 * @param $str
	 * @return bool
	 */
	public function nullable($str): bool
	{
		if (is_null($str)) {
			return true;
		}
		if(trim($str)===''){
			return true;
		}
		if(is_array($str)){
			return count($str) === 0;
		}
		return false;
	}

    /**
     * url
     *
     * @param $str
     * @return bool
     */
    public function url($str): bool
	{
        return (filter_var($str, FILTER_VALIDATE_URL) !== false);
    }

    /**
     * @param $str
     * @param $field
     * @return bool
     */
    public function match($str, $field): bool
	{
        return $str === $this->getValue($field);

    }

    /**
     * Strict paswword
     * @param $str
     * @return bool
     */
    public function password($str): bool
	{
        $regex = "#^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{1,}#";
        return (bool)preg_match($regex, $str);
    }

    /**
     * regex
     *
     * @param $str
     * @param $regex
     * @return bool
     */
    public function regex($str, $regex): bool
	{
        return (bool)preg_match($regex, $str);
    }

    /**
     * regex_not
     *
     * Забранява символите в рег. израз
     * 'regex_not:#[{}$@&()=<>]#i'
     *
     * @param $str
     * @param $regex
     * @return bool
     */
    public function regex_not($str, $regex): bool
	{
        return !preg_match($regex, $str);
    }

    /**
     * required
     *
     * @param $str
     * @return bool|null
	 */
    public function required($str): bool
	{
        //return is_array($str) ? (bool)count($str) : (trim($str) !== '');

		if (is_null($str)) {
			return false;
		}

        if (is_array($str)) {
            if (isset($str['error'])) {
                return $this->file($str);
            }
			return (bool)count($str);
		}

		return trim($str) !== '';
	}

    /**
     *  file
     *  The field under validation must be a successfully uploaded file.
     * @param $file
     * @return bool
     */
    public function file($file): bool
	{
        if (is_array($file['error'])) {
            $error = (int)$file['error'][0];
        } else {
            $error = (int)$file['error'];
        }

        return $error !== 4;
    }

	/**
	 * size:value
	 * The field under validation must have a size matching the given value.
	 * For string data, value corresponds to the number of characters.
	 * For numeric data, value corresponds to a given integer value.
	 * For an array, size corresponds to the count of the array.
	 * @param $str
	 * @param $value
	 * @return bool
	 */
    public function size($str, $value): bool
	{
		if (is_numeric($str)) {
			return $str === $value;

		}

		if (is_string($str) && !is_numeric($str)) {
			return mb_strlen($str) === (int)$value;

		}

		if (is_array($str)) {
			$count = count($str);
			return $count === (int)$value;
		}
	}

    /**
     * exists
     *
     * Проверява за съществуваш запис
     * връща false ако не съществува (обратно на unique)
     * в База Данни пр. (exists:table.col)
     *
     * @param $str
     * @param $field
     * @return bool
     */
    public function exists($str, $field): bool
	{
        return $this->unique($str, $field) === false;
    }

    /**
     * unique
     *
     * Проверява за уникален запис
     * в База Данни пр. (unique:table.col)
     *
     * @param $str
     * @param $field
     * @return bool
     */
    public function unique($str, $field): bool
	{

        [$table, $col] = explode('.', $field);

        $db = MySqlPDOConnection::getInstance()->getConnection();

        $sql = "SELECT COUNT(*) FROM $table WHERE $col= :str";
        $sth = $db->prepare($sql);
        $sth->bindParam(':str', $str, \PDO::PARAM_STR);
        $sth->execute();

        $result = $sth->fetch(\PDO::FETCH_NUM);

        if ((int)$result[0] !== 0) {
            return false;
        }

        return true;
    }

    /**
     *
     * Проверява за уникален запис
     * в База Данни с изключение
     * пр. (unique_except:table.col.exc-col.exc-col-id)
     *
     * @param $str
     * @param $field
     * @return bool
     */
    public function unique_except($str, $field): bool
	{
        [$table, $col, $exc_col, $exc_col_val] = explode('.', $field);

        $db = MySqlPDOConnection::getInstance()->getConnection();

        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$col}= :str AND {$exc_col} != ($exc_col_val)";
        $sth = $db->prepare($sql);
        $sth->bindParam(':str', $str, \PDO::PARAM_STR);
        $sth->execute();

        $result = $sth->fetch(\PDO::FETCH_NUM);

        if ((int)$result[0] !== 0) {
            return false;
        }

        return true;

    }

    /**
     * @param $value / $request file input under validation
     *
     * @param $attr / rules "mimes:jpeg,bmp,gif"
     * @return bool
     */
    public function mimes($file, $attr): bool
	{
        $mimes = explode(',', $attr);

        if (is_array($file['error'])) {
            foreach ($file['error'] as $key => $error) {

                if ($error == UPLOAD_ERR_OK) {
                    $extension = strtolower(get_file_extension($file['name'][$key]));
                    if (!in_array($extension, $mimes)) {
                        $this->validatedFilename = $file['name'][$key];
                        return false;
                    }
                }
            }

        } else {
            if ($file['error'] == UPLOAD_ERR_OK) {
                $extension = strtolower(get_file_extension($file['name']));
                if (!in_array($extension, $mimes)) {
                    $this->validatedFilename = $file['name'];
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $file
     * @param $arg
     * @return bool
     */
    public function filesize($file, $arg): bool
	{
        $size = (int)$arg;

        if ($this->file($file)) {
            if (is_array($file['error'])) {
                foreach ($file['error'] as $key => $error) {
                    if ($error === UPLOAD_ERR_OK) {
                        $this->validatedFilename = $file['name'][$key];
                        return $file['size'][$key] < $size * 1048576; //MB
                    }
                }

            } else if ($file['error'] === UPLOAD_ERR_OK) {
				$this->validatedFilename = $file['name'];
				return $file['size'] < $size * 1048576;
			}

        }
    }
}
