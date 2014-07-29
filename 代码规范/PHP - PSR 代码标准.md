#PHP PSR代码标准


PSR-0 自动加载规范
==========
下面描述了关于自动加载器特性强制性要求：

强制性
---------

* 一个完全标准的命名空间必须要有以下的格式结构`\<Vendor Name>\(<Namespace>\)*<Class Name>`
* 命名空间必须有一个顶级的组织名称 ("Vendor Name").
* 命名空间中可以根据情况使用任意数量的子空间
* 从文件系统中加载源文件的时，命名空间中的分隔符将被映射为 `DIRECTORY_SEPARATOR`
* 命名空间中的类名中的`_`没有特殊含义，也将被作为`DIRECTORY_SEPARATOR`对待.
* 标准的命名空间和类从文件系统加载源文件时只需要加上`.php`后缀即可
* 组织名，空间名，类名都可以随意使用大小写英文字符的组合

示例
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

命名空间和类名中的下划线
-----------------------------------------

* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

以上是我们为轻松实现自动加载特性设定的最低标准。你可以利用下面这个可以自动加载 PHP 5.3 类的SplClassLoader来测试你的代码是否符合以上这些标准。

实例
----------------------

下面是一个函数实例简单展示如何使用上面建议的标准进行自动加载
```
<?php

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
```

SplClassLoader实现
-----------------------------

下面的gist是一个可以按照上面建议的自动加载特性来加载类的SplClassLoader实例。这也是我们当前在PHP5.3中依据以上标准加载类时推荐的方。

* [http://gist.github.com/221634](http://gist.github.com/221634)



----


PSR-1 基本代码规范
=====================

本节标准包含了成为标准代码所需要的基本元素，以确保开源出来的PHP代码之间有较高度的技术互用性。

在 [RFC 2119][]中的特性关键词"必须"(MUST)，“不可”(MUST NOT)，“必要”(REQUIRED)，“将会”(SHALL)，“不会”(SHALL NOT)，“应当”(SHOULD)，“不应”(SHOULD NOT)，“推荐”(RECOMMENDED)，“可以”(MAY)和“可选”(OPTIONAL)在这文档中将被用来描述。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/hfcorriez/fig-standards/blob/zh_CN/接受/PSR-0.md


1. 大纲
-----------

- 源文件`必须`只使用 `<?php` 和 `<?=` 标签。

- 源文件中`必须`只使用不带BOM的UTF-8作为PHP代码。

- 源文件`应当`只声明符号（类，函数，常量等...）或者引起副作用（例如：生成输出，修改.ini配置等）,但`不应`同时做这两件事。

- 命名空间和类`必须`遵守 [PSR-0][]。

- 类名`必须`使用骆驼式`StudlyCaps`写法 (译者注：驼峰式的一种变种，后文将直接用`StudlyCaps`表示)。

- 类中的常量`必须`使用全大写和下划线分隔符。

- 方法名`必须`使用驼峰式`cameCase`写法(译者注：后文将直接用`camelCase`表示)。


2. 文件
--------

### 2.1. PHP标签

PHP代码`必须`只使用长标签`<?php ?>`或者短输出式`<?= ?>`标签；它`不可`使用其他的标签变种。

### 2.2. 字符编码

PHP代码`必须`只使用不带BOM的UTF-8。

### 2.3. 副作用

一个文件`应当`声明新符号 (类名，函数名，常量等)并且不产生副作用，或者`应当`执行有副作用的逻辑，但不能同时做这两件事。

短语"副作用"意思是不直接执行逻辑的类，函数，常量等 *仅包括文件*

“副作用”包含但不局限于：生成输出，显式地使用`require`或`include`，连接外部服务，修改ini配置，触发错误或异常，修改全局或者静态变量，读取或修改文件等等

下面是一个既包含声明又有副作用的示例文件；即应避免的例子：

```
<?php
// side effect: change ini settings
ini_set('error_reporting', E_ALL);

// side effect: loads a file
include "file.php";

// side effect: generates output
echo "<html>\n";

// declaration
function foo()
{
    // function body
}
```

下面是一个仅包含声明的示例文件；即需要提倡的例子：

```
<?php
// declaration
function foo()
{
    // function body
}

// conditional declaration is *not* a side effect
if (! function_exists('bar')) {
    function bar()
    {
        // function body
    }
}
```


3. 命名空间和类名
----------------------------

命名空间和类名必须遵守 [PSR-0][].

这意味着每个类必须单独一个源文件，并且至少有一级命名空间：顶级的组织名。

类名必须使用骆驼式`StudlyCaps`写法。

PHP5.3之后的代码`必须`使用正式的命名空间
例子：

```
<?php
// PHP 5.3 and later:
namespace Vendor\Model;

class Foo
{
}
```

PHP5.2.x之前的代码应当用伪命名空间`Vendor_`作为类名的前缀

```
<?php
// PHP 5.2.x and earlier:
class Vendor_Model_Foo
{
}
```

4. 类常量，属性和方法
-------------------------------------------

术语“类”指所有的类，接口和特性(traits)

### 4.1. 常量

类常量`必须`使用全大写，并使用分隔符作为下划线。
例子：

```
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```

### 4.2. 属性

本手册有意避免推荐使用`$StulyCaps`，`$camelCase`或者`unser_score`作为属性名字

不管名称如何约定，它`应当`在一个合理范围内保持一致。这个范围可能是组织层，包层，类层，方法层。

### 4.3. 方法

方法名必须用`camelCase()`写法。

------

PSR-2 代码样式规范
==================

本手册是 [PSR-1][]基础代码规范的继承和扩展

本指南的意图是为了减少不同开发者在浏览代码时减少认知的差异。 为此列举一组如何格式化PHP代码的共用规则。

各个成员项目的共性组成了本文的样式规则。当不同的开发者在不同的项目中合作时，将会在这些不同的项目中使用一个共同的标准。 因此，本指南的好处不在于规则本身，而在于共用这些规则。

在 [RFC 2119][]中的特性关键词"必须"(MUST)，“不可”(MUST NOT)，“必要”(REQUIRED)，“将会”(SHALL)，“不会”(SHALL NOT)，“应当”(SHOULD)，“不应”(SHOULD NOT)，“推荐”(RECOMMENDED)，“可以”(MAY)和“可选”(OPTIONAL)在这文档中将被用来描述。

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/hfcorriez/fig-standards/blob/zh_CN/接受/PSR-0.md
[PSR-1]: https://github.com/hfcorriez/fig-standards/blob/zh_CN/接受/PSR-1-basic-coding-standard.md


1. 大纲
-----------

- 代码必须遵守 [PSR-1][]。

- 代码`必须`使用4个空格的缩进，而不是制表符。

- 一行代码长度`不应`硬性限制；软限制`必须`为120个字符；也`应当`是80个字符或者更少。

- 在`namespace`声明下面`必须`有一个空行，并且`use`声明代码块下面也`必须`有一个空行。

- 类的左花括号`必须`放到下一行，右花括号`必须`放在类主体的下一行。

- 方法的左花括号`必须`放在下一行，右花括号`必须`放在方法主体下面。

- 所有的属性和方法`必须`有可见性(译者注：Public, Protect, Private)声明；`abstract`和`final`声明`必须`在可见性之前；`static`声明`必须`在可见性之后。

- 控制结构的关键词`必须`在后面有一个空格； 方法和函数`不可`有。

- 控制结构的左花括号`必须`放在同一行，右花括号`必须`放在控制主体的下一行。

- 控制结构的左括号后面`不可`有空格，右括号之前`不可`有空格。

### 1.1. 示例

本示例包含上面的一些规则简单展示：

```
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class Foo extends Bar implements FooInterface
{
    public function sampleFunction($a, $b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // method body
    }
}
```

2. 概括
----------

### 2.1 基础代码规范

代码`必须`遵守 [PSR-1][] 的所有规则。

### 2.2 文件

所有的PHP文件`必须`使用Unix LF(换行)作为行结束符。

所有PHP文件`必须`以一个空行结束。

纯PHP代码的文件关闭标签`?>``必须`省略

### 2.3. 行

行长度`不可`有硬限制。

行长度的软限制`必须`是120个字符；对于软限制，自动样式检查器`必须`警告但`不可`报错。

行实际长度`不应`超过80个字符；较长的行`应当`被拆分成多个不超过80个字符的后续行。

在非空行后面`不可`有空格。

空行`可以`用来改善可读性和区分相关的代码块。

一行`不应`多于一个语句。

### 2.4. 缩进

代码`必须`使用4个空格的缩进，并且`不可`使用制表符作为缩进。

> 注意：只用空格，不和制表符混合使用，将会对避免代码差异，补丁，历史和注解中的一些问题有帮助。使用空格还可以使调整细微的缩进来改进行间对齐变得非常简单。

### 2.5. 关键词和 True/False/Null

PHP [keywords][] `必须`使用小写。

PHP常量`true`, `false`和`null``必须`使用小写。

[keywords]: http://php.net/manual/en/reserved.keywords.php


3. Namespace和Use声明
---------------------------------

如果存在，`namespace`声明之后`必须`有一个空行。

如果存在，所有的`use`声明`必须`放在`namespace`声明的下面。

一个`use`关键字`必须`只用于一个声明。

在`use`声明代码块后面`必须`有一个空行。

示例:

```
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... additional PHP code ...

```


4. 类，属性和方法
-----------------------------------

术语“类”指所有的类，接口和特性（traits）。

### 4.1. 扩展和继承

一个类的`extends`和`implements`关键词`必须`和类名在同一行。

类的左花括号`必须`放在下面自成一行；右花括号必须放在类主体的后面自成一行。


```
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constants, properties, methods
}
```

`implements`一个列表`可以`被拆分为多个有一次缩进的后续行。如果这么做，列表的第一项`必须`要放在下一行，并且每行`必须`只有一个接口。

```
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // constants, properties, methods
}
```

### 4.2. 属性

所有的属性`必须`声明可见性。

`var`关键词`不可`用来声明属性。

一个语句`不可`声明多个属性。

属性名称`不应`使用单个下划线作为前缀来表明保护或私有的可见性。

一个属性声明看起来应该下面这样的。

```
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3. 方法

所有的方法`必须`声明可见性。

方法名`不应`只使用单个下划线来表明是保护或私有的可见性。

方法名在声明之后`不可`跟随一个空格。左花括号`必须`放在下面自成一行，并且右花括号`必须`放在方法主体的下面自成一行。左括号后面`不可`有空格，右括号前面`不可`有空格。

一个方法定义看来应该像下面这样。 注意括号，逗号，空格和花括号：

```
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```

### 4.4. 方法参数

在参数列表中，逗号之前`不可`有空格，逗号之后`必须`要有一个空格。

方法中有默认值的参数必须放在参数列表的最后面。

```
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```

参数列表`可以`被分为多个有一次缩进的多个后续行。如果这么做，列表的第一项`必须`放在下一行，并且每行`必须`只放一个参数。

当参数列表被分为多行，右括号和左花括号`必须`夹带一个空格放在一起自成一行。

```
<?php
namespace Vendor\Package;

class ClassName
{
    public function aVeryLongMethodName(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = []
    ) {
        // method body
    }
}
```

### 4.5. `abstract`，`final`和 `static`

如果存在，`abstract`和`final`声明必须放在可见性声明前面。

如果存在，`static`声明`必须`跟着可见性声明。

```
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // method body
    }
}
```

### 4.6. 调用方法和函数

要调用一个方法或函数，在方法或者函数名和左括号之间`不可`有空格，左括号之后`不可`有空格，右括号之前`不可`有空格。函数列表中，逗号之前`不可`有空格，逗号之后`必须`有一个空格。

```
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

参数列表`可以`被拆分成多个有一个缩进的后续行。如果这么做，列表中的第一项必须放在下一行，并且每一行`必须`只有一个参数。

```
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

5. 控制结构
---------------------

对于控制结构的样式规则概括如下：

- 控制结构关键词之后`必须`有一个空格
- 左括号之后`不可`有空格
- 右括号之前`不可`有空格
- 在右括号和左花括号之间`必须`有一个空格
- 代码主体`必须`有一次缩进
- 右花括号`必须`主体的下一行

每个结构的主体`必须`被括在花括号里。这结构看上去更标准化，并且当加新行的时候可以减少引入错误的可能性。

### 5.1. `if`，`elseif`，`else`

一个`if`结构看起来应该像下面这样。注意括号，空格，花括号的位置；并且`else`和`elseif`和前一个主体的右花括号在同一行。

```
<?php
if ($expr1) {
    // if body
} elseif ($expr2) {
    // elseif body
} else {
    // else body;
}
```

关键词`elseif``应该`替代`else if`使用以保持所有的控制关键词像一个单词。


### 5.2. `switch`，`case`

一个`switch`结构看起来应该像下面这样。注意括号，空格和花括号。`case`语句必须从`switch`处缩进，并且`break`关键字（或其他中止关键字）`必须`和`case`主体缩进在同级。如果一个非空的`case`主体往下落空则`必须`有一个类似`// no break`的注释。

```
<?php
switch ($expr) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Third case, return instead of break';
        return;
    default:
        echo 'Default case';
        break;
}
```


### 5.3. `while`，`do while`

一个`while`语句看起来应该像下面这样。注意括号，空格和花括号的位置。

```
<?php
while ($expr) {
    // structure body
}
```

同样的，一个`do while`语句看起来应该像下面这样。注意括号，空格和花括号的位置。

```
<?php
do {
    // structure body;
} while ($expr);
```

### 5.4. `for`

一个`for`语句看起来应该像下面这样。注意括号，空格和花括号的位置。

```
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### 5.5. `foreach`

一个`foreach`语句看起来应该像下面这样。注意括号，空格和花括号的位置。

```
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### 5.6. `try`, `catch`

一个`try catch`语句看起来应该像下面这样。注意括号，空格和花括号的位置。

```
<?php
try {
    // try body
} catch (FirstExceptionType $e) {
    // catch body
} catch (OtherExceptionType $e) {
    // catch body
}
```

6. 闭包
-----------

闭包在声明时`function`关键词之后`必须`有一个空格，并且`use`之前也需要一个空格。

左花括号`必须`在同一行，右花括号`必须`在主体的下一行。

参数列表和变量列表的左括号之后`不可`有空格，其右括号之前也`不可`有空格。

在参数列表和变量列表中，逗号之前`不可`有空格，逗号之后`必须`有空格。

闭包带默认值的参数`必须`放在参数列表后面。

一个闭包声明看起来应该像下面这样。注意括号，空格和花括号的位置。

```
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

参数和变量列表`可以`被分成多个带一次缩进的后续行。如果这么做，列表的第一项`必须`放在下一行，并且一行`必须`只放一个参数或变量。

当最终列表（不管是参数还是变量）被分成多行，右括号和左花括号`必须`夹带一个空格放在一起自成一行。

下面是一个参数和变量列表被分割成多行的示例。

```
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // body
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};

$longArgs_longVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // body
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};
```

注意如果在函数或者方法中把闭包作为一个参数调用，如上格式规则同样适用。

```
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // body
    },
    $arg3
);
```


7. 结论
--------------

在该指南中有很多风格的元素和做法有意被忽略掉。这些包括但不局限于：

- 全局变量和全局常量的声明

- 方法声明

- 操作符和赋值

- 行间对齐

- 注释和文档块

- 类名给你前缀和后缀

- 最佳实践

以后的建议`可以`修改和扩展该指南以满足这些或其他风格的元素和实践。

        

---

PSR-3 日志接口
================

本文档用来描述日志类库的通用接口。

主要目标是让类库获得一个`Psr\Log\LoggerInterface`对象并且使用一个简单通用的方式来写日志。有自定义需求的框架和CMS`可以`根据情况扩展这个接口，但`应当`保持和该文档的兼容性，这将确保使用第三方库和应用能统一的写应用日志。

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

关键词`实现者`在这个文档被解释为：在日志相关的库和框架实现`LoggerInterface`接口的人。用这些实现的人都被称作`用户`。

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. 规范
-----------------

### 1.1 基础

- `LoggerInterface`暴露八个接口用来记录八个等级(debug, info, notice, warning, error, critical, alert, emergency)的日志。

- 第九个方法是`log`，接受日志等级作为第一个参数。用一个日志等级常量来调用这个方法的结果`必须`和调用具体等级方法的一致。如果具体的实现不知道传入的不按规范的等级来调用这个方法`必须`抛出一个`Psr\Log\InvalidArgumentException`。用户`不应`自定义一个当前不支持的未知等级。

[RFC 5424]: http://tools.ietf.org/html/rfc5424

### 1.2 消息

- 每个方法都接受字符串，或者有`__toString`方法的对象作为消息。实现者可以对传入的对象有特殊的处理。如果不是，实现者`必须`将它转换成字符串。

- 消息`可以`包含`可以`被上下文数组的数值替换的占位符。

  占位符名字`必须`和上下文数组键名对应。

  占位符名字`必须`使用使用一对花括号为分隔。在占位符和分隔符之间`不能`有任何空格。

  占位符名字`应该`由`A-Z`，`a-z`，`0-9`，下划线`_`和句号`.`。其它的字符作为以后占位符规范的保留。

  实现者可以使用占位符来实现不同的转义和翻译日志成文。用户在不知道上下文数据是什么的时候`不应`提前转义占位符。

  下面提供一个占位符替换的例子，仅作为参考：

  ```
  /**
   * Interpolates context values into the message placeholders.
   */
  function interpolate($message, array $context = array())
  {
      // build a replacement array with braces around the context keys
      $replace = array();
      foreach ($context as $key => $val) {
          $replace['{' . $key . '}'] = $val;
      }

      // interpolate replacement values into the message and return
      return strtr($message, $replace);
  }

  // a message with brace-delimited placeholder names
  $message = "User {username} created";

  // a context array of placeholder names => replacement values
  $context = array('username' => 'bolivar');

  // echoes "Username bolivar created"
  echo interpolate($message, $context);
  ```

### 1.3 上下文

- 每个方法接受一个数组作为上下文数据，用来存储不适合在字符串中填充的信息。数组可以包括任何东西。实现者`必须`确保他们对上下文数据足够的掌控。在上下文中一个给定值`不可`抛出一个异常，也`不可`产生任何PHP错误，警告或者提醒。

- 如果在上下文中传入了一个`异常`对象，它必须以`exception`作为键名。记录异常轨迹是通用的模式，如果日志底层支持这样也是可以被允许的。实现者在使用它之前`必须`验证`exception`的键值是不是一个`异常`对象，因为它`可以`允许是任何东西。

### 1.4 助手类和接口

- `Psr\Log\AbstractLogger`类让你非常简单的实现和扩展`LoggerInterface`接口以实现通用的`log`方法。其他八个方法将会把消息和上下文转发给它。

- 类似的，使用`Psr\Log\LoggerTrait`只需要你实现通用的`log`方法。记住traits不能实现接口前，你依然需要`implement LoggerInterface`。

- `Psr\Log\NullLogger`是和接口一个提供的。它`可以`为使用接口的用户提供一个后备的“黑洞”。如果上下文数据非常重要，这不失为一个记录日志更好的办法。

- `Psr\Log\LoggerAwareInterface`只有一个`setLogger(LoggerInterface $logger)`方法可以用来随意设置一个日志记录器。

- `Psr\Log\LoggerAwareTrait`trait可以更简单的实现等价于接口。通过它可以访问到`$this->logger`。

- `Psr\Log\LogLevel`类拥有八个等级的常量。

2. 包
----------

作为[psr/log](https://packagist.org/packages/psr/log) 的一部分，提供接口和相关异常类的一些描述以及一些测试单元用来验证你的实现。

3. `Psr\Log\LoggerInterface`
----------------------------

```
<?php

namespace Psr\Log;

/**
 * Describes a logger instance
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data, the only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * for the full interface specification.
 */
interface LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array());

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array());

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array());

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array());

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array());

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array());

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array());

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array());

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array());
}
```

4. `Psr\Log\LoggerAwareInterface`
---------------------------------

```
<?php

namespace Psr\Log;

/**
 * Describes a logger-aware instance
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger);
}
```

5. `Psr\Log\LogLevel`
---------------------

```
<?php

namespace Psr\Log;

/**
 * Describes log levels
 */
class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
}
```
