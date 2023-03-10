<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2021 seffeng
 */
namespace Seffeng\Rules;

class IDNumber
{
    /**
     * 性别[男]
     * @var integer
     */
    const GENDER_MALE = 'male';

    /**
     * 性别[女]
     * @var integer
     */
    const GENDER_FEMALE  = 'female';

    /**
     *
     * @var string
     */
    protected $regex = '/^\d{17}[0-9x]$/i';

    /**
     *
     * @var string
     */
    protected $value;

    /**
     *
     * @var string
     */
    protected $location;

    /**
     * 是否检测地区（前6位）
     * @var boolean
     */
    protected $isStrict = false;

    /**
     * 是否显示所属地
     * @var boolean
     */
    protected $isLocation = true;

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
     * @var integer
     */
    protected $minYear = 1800;

    /**
     *
     * @var integer
     */
    protected $maxYear = 2999;

    /**
     *
     * @var integer
     */
    protected $maxMonth = 12;

    /**
     *
     * @var integer
     */
    protected $maxDay = 31;

    /**
     *
     * @var string
     */
    protected $birthday;

    /**
     *
     * @var string
     */
    protected $gender;

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
        $this->setValue($value);
        if (preg_match($this->regex, $this->getValue()) === 1) {
            return $this->validBirthday() && $this->validLocation() && $this->compareIDNumber();
        }
        return false;
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @param string $value
     */
    protected function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @param string $location
     */
    protected function setLocation(string $location)
    {
        $this->location = $location;
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @param bool $strict
     */
    public function setIsStrict(bool $isStrict)
    {
        $this->isStrict = $isStrict;
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @return boolean
     */
    public function getIsStrict()
    {
        return $this->isStrict;
    }

    /**
     *
     * @author zxf
     * @date   2023-03-10
     * @param boolean $isLocation
     */
    public function setIsLocation(bool $isLocation)
    {
        $this->isLocation = $isLocation;
    }

    /**
     *
     * @author zxf
     * @date   2023-03-10
     * @return boolean
     */
    public function getIsLocation()
    {
        return $this->isLocation;
    }

    /**
     *
     * @author zxf
     * @date   2021年10月28日
     * @param int $year
     */
    public function setMinYear(int $year)
    {
        $this->minYear = $year;
    }

    /**
     *
     * @author zxf
     * @date   2021年10月28日
     * @return number
     */
    public function getMinYear()
    {
        return $this->minYear;
    }

    /**
     *
     * @author zxf
     * @date   2021年10月28日
     * @param int $year
     */
    public function setMaxYear(int $year)
    {
        $this->maxYear = $year;
    }

    /**
     *
     * @author zxf
     * @date   2021年10月28日
     * @return number
     */
    public function getMaxYear()
    {
        return $this->maxYear;
    }

    /**
     *
     * @author zxf
     * @date   2021年10月28日
     * @param string $birthday
     */
    protected function setBirthday(string $birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     *
     * @author zxf
     * @date   2021年10月28日
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     *
     * @author zxf
     * @date   2021年10月28日
     * @return number
     */
    public function getMaxMonth()
    {
        return $this->maxMonth;
    }

    /**
     *
     * @author zxf
     * @date   2022年3月1日
     * @return string
     */
    public function getGender()
    {
        $gender = isset($this->getValue()[16]) ? $this->getValue()[16] : null;
        if (is_null($gender)) {
            return '';
        } elseif ($gender % 2 === 0) {
            return self::GENDER_FEMALE;
        } else {
            return self::GENDER_MALE;
        }
    }

    /**
     *
     * @author zxf
     * @date   2022年3月1日
     * @return string
     */
    public static function fetchGenderItems()
    {
        return [
            self::GENDER_FEMALE => '女',
            self::GENDER_MALE => '男'
        ];
    }

    /**
     *
     * @author zxf
     * @date   2022年3月1日
     * @return string
     */
    public function getGenderName()
    {
        return isset(static::fetchGenderItems()[$this->getGender()]) ? static::fetchGenderItems()[$this->getGender()] : '';
    }

    /**
     *
     * @author zxf
     * @date   2021年10月28日
     * @return number
     */
    public function getMaxDay()
    {
        return $this->maxDay;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月29日
     * @return \DateInterval
     */
    public function getYears()
    {
        $startDate = new \DateTime($this->getBirthday());
        $endDate = new \DateTime(date('Y-m-d'));
        return $endDate->diff($startDate);
    }

    /**
     *
     * @author zxf
     * @date   2021年7月1日
     * @return string
     */
    protected function calculateY()
    {
        $s = 0;
        for ($i = 0; $i < 17; $i++) {
            $s += intval($this->getValue()[$i]) * $this->wi[$i];
        }
        $mod = $s % $this->mod;
        return isset($this->y[$mod]) ? $this->y[$mod] : '';
    }

    /**
     *
     * @author zxf
     * @date   2021年7月1日
     * @return boolean
     */
    protected function compareIDNumber()
    {
        return strtoupper($this->getValue()) === (substr($this->getValue(), 0, 17) . $this->calculateY());
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @return boolean
     */
    protected function validBirthday()
    {
        $year = substr($this->getValue(), 6, 4);
        $month = substr($this->getValue(), 10, 2);
        $day = substr($this->getValue(), 12, 2);
        $this->setBirthday($year . '-' . $month . '-' . $day);
        return ($year >= $this->getMinYear() && $year <= $this->getMaxYear() && $month <= $this->getMaxMonth() && $day <= $this->getMaxDay())
            && strtotime($this->getBirthday()) && (date('t', strtotime($year . '-' . $month . '-' . '01')) >= $day);
    }

    /**
     *
     * @author zxf
     * @date   2021年9月30日
     * @return boolean
     */
    protected function validLocation()
    {
        if (!$this->getIsStrict() && !$this->getIsLocation()) {
            return true;
        }
        $code = substr($this->getValue(), 0, 6);
        $items = json_decode(file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'idnumber-location.json'), true);
        if (isset($items[$code])) {
            $this->getIsLocation() && $this->setLocation($items[$code]);
            return true;
        }
        return !$this->getIsStrict();
    }
}
