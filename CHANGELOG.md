### 更新日志

* 2022-04-29

  * 增加密码验证等级

  ```php
  # 属性
  /**
   * 验证正则
   * @var string
   */
  protected $regex
  /**
   * 验证等级，基于正则增加验证
   * [1-仅验证正则，2-相同字符必须小于$number个，3-连续字符必须小于$number个，4-(2、3)]
   * [2：aa允许，aaa不允许，11允许，111不允许]$number=3
   * [3：ab允许，abc不允许，12允许，123不允许]$number=3
   * [4：aa、ab允许，aaa、abc均不允许，11、12允许，111、123均不允许]$number=3
   * @var string
   */
  protected $level = 1;
  /**
   * 相同字符和连续字符个数限制
   * @var string
   */
  protected $number = 3;
  ```

---

* 2021-10-28

  * 增加身份证验证

  ```php
  # 属性
  
  /**
   * 是否检测地区（前6位）
   * @var boolean
   */
  protected $isStrict = true;
  
  # 方法
  /**
   * 获取性别[female， male]
   */
  getGender():string 
  /**
   * 获取性别[女， 男]
   */
  getGenderName():string
  /**
   * 获取所属地[XX省XX市XXX]
   */
  getLocation():string
  /**
   * 获取出生日期
   */
  getBirthday():string
  /**
   * 获取年龄
   * [y-年,m-月,d-日,days-总天数]
   * [y-2,m-3,d-5,days-827]代表2岁零3月5天，共827天
   */
  getYears(): DateInterval
  ```
  
