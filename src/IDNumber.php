<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2021 seffeng
 */
namespace Seffeng\Rules;

class IDNumber
{
    /**
     *
     * @var string
     */
    protected $regex = '/^\d{17}[0-9x]$/i';

    /**
     * 对应位置的加权因子
     * @var array
     */
    protected $wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

    /**
     * 对应的校验码
     * @var array
     */
    protected $y = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

    /**
     *
     * @var integer
     */
    protected $mod = 11;

    /**
     *
     * @author zxf
     * @date   2021年7月7日
     * @param string $regex
     */
    public function __construct(string $regex = null)
    {
        if (!is_null($regex) && $regex !== '') {
            $this->regex = $regex;
        }
    }

    /**
     *
     * @author zxf
     * @date   2021年7月7日
     * @param string $value
     * @return boolean
     */
    public function passes(string $value)
    {
        if (preg_match($this->regex, $value) === 1) {
            return $this->validBirthday($value) && $this->compareIDNumber($value);
        }
        return false;
    }
    /**
     *
     * @author zxf
     * @date   2021年7月1日
     * @param string $value
     * @return string
     */
    protected function calculateY(string $value)
    {
        $s = 0;
        for ($i = 0; $i < 17; $i++) {
            $s += intval($value[$i]) * $this->wi[$i];
        }
        $mod = $s % $this->mod;
        return isset($this->y[$mod]) ? $this->y[$mod] : '';
    }

    /**
     *
     * @author zxf
     * @date   2021年7月1日
     * @param string $value
     * @return boolean
     */
    protected function compareIDNumber(string $value)
    {
        return strtoupper($value) === (substr($value, 0, 17) . $this->calculateY($value));
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @param string $value
     * @return boolean
     */
    protected function validBirthday(string $value)
    {
        $year = substr($value, 6, 4);
        $month = substr($value, 10, 2);
        $day = substr($value, 12, 2);
        return strtotime($year . $month . $day) && (date('t', strtotime($year . $month . '01')) >= $day);
    }
}
