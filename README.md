## Rules

### 安装

```shell
# 安装
$ composer require seffeng/rules
```

### 目录说明

```
├─src
│    IDNumber.php            身份证号
│    Password.php            密码
│    Phone.php               手机号
```

### 示例

```php
  use Seffeng\Rules\IDNumber;
  
  /**
   * Test.php
   * 方法验证示例
   */
  public function test()
  {
      $rule = new IDNumber();
      $value = '123456789123456789';
      var_dump($rule->passes($value));exit;
  }
```

### 备注

1、仅支持大陆18位身份证验证。

### 更新

* [changelog](./CHANGELOG.md)
  * 2022-04-29
  * 2021-10-28