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
        return boolval(preg_match($this->regex, $value));
    }
}
