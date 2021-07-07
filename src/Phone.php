<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2021 seffeng
 */
namespace Seffeng\Rules;

class Phone
{
    /**
     *
     * @var string
     */
    protected $regex = '/^1\d{10}$/';

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
