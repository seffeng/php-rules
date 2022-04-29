<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2021 seffeng
 */
namespace Seffeng\Rules;

class Password
{
    /**
     * 必须包含字母和数字
     * @var string
     */
    protected $regex = '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/';
    /**
     * 至少包含字母、数字、特殊字符其中两种
     * @var string
     */
    // protected $regex = '/^(?![0-9]+$)(?![a-zA-Z]+$)(?![\.\+\-\*\/\?\$\|\^\[\]\{\}\(\)&~!@#<>,;:=]+$)[0-9A-Za-z\.\+\-\*\/\?\$\|\^\[\]\{\}\(\)&~!@#<>,;:=]{6,30}$/';

    /**
     * 必须包含字母、数字、特殊字符三种
     * @var string
     */
    // protected $regex = '/^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[\.\+\-\*\/\?\$\|\^\[\]\{\}\(\)&~!@#<>,;:=])[0-9a-zA-Z\.\+\-\*\/\?\$\|\^\[\]\{\}\(\)&~!@#<>,;:=]{6,30}$/';

    /**
     *
     * @var string
     */
    protected $value;

    /**
     * 验证等级
     * @var integer
     */
    protected $level = 1;

    /**
     * 相同字符连续字符个数限制
     * @var integer
     */
    protected $number = 3;

    /**
     * 验证等级[不能连续相同字符]
     * @var integer
     */
    const LEVEL_II = 2;
    /**
     * 验证等级[不能连续顺序字符]
     * @var integer
     */
    const LEVEL_III = 3;
    /**
     * 验证等级[不能连续相同字符和连续顺序字符]
     * @var integer
     */
    const LEVEL_IV = 4;

    /**
     *
     * @author zxf
     * @date   2021年7月1日
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
     * @date   2021年7月1日
     * @param string $value
     * @return boolean
     */
    public function passes(string $value)
    {
        $this->value = $value;
        $valid = boolval(preg_match($this->getRegex(), $this->getValue()));
        if ($valid) {
            if ($this->getIsLevelII()) {
                $valid = !$this->levelII();
            } elseif ($this->getIsLevelIII()) {
                $valid = !$this->levelIII();
            } elseif ($this->getIsLevelIV()) {
                $valid = !$this->levelIV();
            }
        }
        return $valid;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月29日
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月28日
     * @param int $level
     */
    public function setNumber(int $number = null)
    {
        $number > 1 && $this->number = $number;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月29日
     * @return number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月28日
     * @param int $level
     */
    public function setLevel(int $level = null)
    {
        $level > 0 && $this->level = $level;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月29日
     * @return number
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月28日
     * @return boolean
     */
    public function getIsLevelII()
    {
        return $this->getLevel() === self::LEVEL_II;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月28日
     * @return boolean
     */
    public function getIsLevelIII()
    {
        return $this->getLevel() === self::LEVEL_III;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月28日
     * @return boolean
     */
    public function getIsLevelIV()
    {
        return $this->getLevel() === self::LEVEL_IV;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月29日
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月29日
     * @return number
     */
    protected function getMaxNumber()
    {
        return $this->getNumber() - 1;
    }

    /**
     * 出现多个相同字符时返回 true，密码验证不通过
     * @author zxf
     * @date   2022年4月28日
     * @return boolean
     */
    protected function levelII()
    {
        if (preg_match('/(\d)\1{' . $this->getMaxNumber() . '}/', $this->getValue())) {
            return true;
        } elseif (preg_match('/([a-zA-Z])\1{' . $this->getMaxNumber() . '}/', $this->getValue())) {
            return true;
        } elseif (preg_match('/([\.\+\-\*\/\?\$\|\^\[\]\{\}\(\)&~!@#<>,;:=])\1{' . $this->getMaxNumber() . '}/', $this->getValue())) {
            return true;
        }
        return false;
    }

    /**
     * 出现连续顺序字符时返回 true，密码验证不通过
     * @author zxf
     * @date   2022年4月28日
     * @return boolean
     */
    protected function levelIII()
    {
        $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $matcharr = str_split($string);
        $strlen = strlen($string);
        $orgarr = str_split($this->getValue());

        foreach ($orgarr as $k => $v) {
            if (isset($orgarr[$k + $this->getMaxNumber()]) && isset($matcharr[$k + $this->getMaxNumber()])) {
                $findkey = array_search($v, $matcharr);
                if ($findkey === false || ($findkey + $this->getMaxNumber()) >= $strlen) {
                    continue;
                }
                $match = 0;
                for ($i = 1; $i <= $this->getMaxNumber(); $i++) {
                    if ($orgarr[$k + $i] == $matcharr[$findkey + $i]) {
                        $match++;
                    }
                }
                if ($match >= $this->getMaxNumber()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     *
     * @author zxf
     * @date   2022年4月28日
     * @return boolean
     */
    protected function levelIV()
    {
        return $this->levelII() || $this->levelIII();
    }
}
