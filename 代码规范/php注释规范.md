#PHP注释规范

##通用注释写法

###一、文件的注释通用样例（普通程序文件，类文件，函数文件，变量定义文件）

```
/**
 * XXXXX的文件
 * 
 * 功能1： xxx
 * 功能2： xxx
 *
 * @file        $Source: /home/doc/php开发注释规范.md  $
 * @package     core
 * @author      Joy <anzhengchao@gmail.com>
 * @version     $Id: php开发注释规范.txt,v 1.1 2014/03/04 20:37:46 Joy Exp $
 * @link        http://www.joychao.cc
 */
```

- `@file` 行在提交了svn之后，会自动被补充为如下样式：

	```
	 * @file        $Source: /home/doc/php开发注释规范.md $
	```

- `@version` 行在提交了svn之后，会自动被补充为如下样式：

	```
	 * @version     $Id: php开发注释规范.txt,v 1.1 2014/03/04 20:37:46 Joy Exp $
	```

- `@package` 是团队事先定义好的，在phpdocumentor里同一package的文件可以给出跟踪的链接。项目开发前需要对其定义。

- `@link` 行后面接的地址是程序开发文档的地址，因为我们目前没有在线的程序开发文档库，所以可不加。
注意注释的排版，左端保持对齐。

#####说明：以上自动更新版本及文件名需要配置svn，具体请自行google *'SVN自动版本号'*

###二、类的注释，使用如下几个tag

```
/**
 * xxxxx类
 * 
 * 功能1：完成xxxx
 * 功能2：完成xxxxx
 *
 * @author      Joy <anzhengchao@gmail.com>
 * @access      public
 * @abstract 
 */
public class DemoClass {
	//code...
}
```
- `@access` (`public`|`private`) 标记类是私有的还是公用的。
- `@abstract`标记该类是个抽象类
虽然我们使用的是php5，但目前程序类的编写仍然沿用的php4，因此`@access`和`@abstract`两项可加可不加。

###三、类的属性定义

```
public class DemoClass {

   /**
    * 当前页码
    *
    * @var integer 
    */
    public $currentPageNumber;
   
   /**
    * 私有变量使用下划线开头
    *
    * @var string
    */
    private $_instance;
}

```

###四、普通函数和类中的函数注释

```
/**
 * 获取头像地址
 *
 * @author Joy <anzhengchao@gmail.com>
 *
 * @param string  $imageName  图片文件名
 * @param integer $size 	   大小
 *
 * @return string
 */
function getAvatarUrl($imageName, $size = 80)
{
    return sprintf(SITE_URL . '/service/images/cropped_%s/'.$imageName, $size);
}

```

顺序按照author、param、return来放，**区块间空行**。

###五、程序段落注释
段落注释和逻辑注释使用如下方式

```
/**
 * 1 如果$_GET['do']等于buy,则购买条码
 */
if($_GET['do'] == 'buy')
{
    // 1.1 验证用户提交变量是否合法
    if($_POST['strCodeNum'])
    {
        
    }
    // 1.2 验证用户提交的码是否可以购买
    
    // 1.3 ..................
} // end if

/**
 * 2 如果$_GET['do']等于list,显示用户选择的条码
 */
if($_GET['do'] == 'list')
{
    // 2.1 验证用户提交变量是否合法
    if($_POST['strCodeNum'])
    {
        
    }
    // 2.2 验证用户提交的码是否可以购买
    
    // 2.3 ..................
} // end if
```

##PHPdoc规范

文档注释，无非“//”和“/**/”两种 ，自己写代码，就那么点，适当写几句就好了；但是一个人总有融入团队的一天，团队的交流不是那几句注释和一张嘴能解决的，还需要通用的注释标准。

PHPDoc是PHP文档注释的一个标准，可以帮助我们在注释文档时有规范，查看别人的代码时更方便。下面的表格是我翻译的WIKI上的PHPDoc，个人英文水平有限，可以参照原文。

文档翻译自：[http://en.wikipedia.org/wiki/Phpdoc](http://en.wikipedia.org/wiki/Phpdoc)


标记|用途|描述
-|-
@abstract| |抽象类的变量和方法
@access|public, private or protected|文档的访问、使用权限. @access private 表明这个文档是被保护的。
@author|张三 <zhangsan@163.com>|文档作者
@copyright|名称 时间|文档版权信息
@deprecated|version|文档中被废除的方法
@deprec| |同 @deprecated
@example|/path/to/example|文档的外部保存的示例文件的位置。
@exception| |文档中方法抛出的异常，也可参照 @throws.
@global|类型：$globalvarname|文档中的全局变量及有关的方法和函数
@ignore| |忽略文档中指定的关键字
@internal| |开发团队内部信息
@link|URL|类似于license 但还可以通过link找到文档中的更多个详细的信息
@name|变量别名|为某个变量指定别名
@magic| |phpdoc.de compatibility
@package|封装包的名称|一组相关类、函数封装的包名称
@param|如 $username 用户名|变量含义注释
@return|如 返回bool|函数返回结果描述，一般不用在void（空返回结果的）的函数中
@see|如 Class Login()|文件关联的任何元素（全局变量，包括，页面，类，函数，定义，方法，变量）。
@since|version|记录什么时候对文档的哪些部分进行了更改
@static| |记录静态类、方法
@staticvar| |在类、函数中使用的静态变量
@subpackage| |子版本
@throws| |某一方法抛出的异常
@todo| |表示文件未完成或者要完善的地方
@var|type|文档中的变量及其类型
@version| |文档、类、函数的版本信息



PHPDoc注释实例：

```
<?php 
 /**
  * start page for webaccess
  *
  * PHP version 5
  *
  * @category  PHP
  * @package   PSI_Web
  * @author    Michael Cramer <BigMichi1@users.sourceforge.net>
  * @copyright 2009 phpSysInfo
  * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
  * @version   SVN: $Id: class.Webpage.inc.php 412 2010-12-29 09:45:53Z Jacky672 $
  * @link      http://phpsysinfo.sourceforge.net
  */
  /**
  * generate the dynamic webpage
  *
  * @category  PHP
  * @package   PSI_Web
  * @author    Michael Cramer <BigMichi1@users.sourceforge.net>
  * @copyright 2009 phpSysInfo
  * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
  * @version   Release: 3.0
  * @link      http://phpsysinfo.sourceforge.net
  */
 class Webpage extends Output implements PSI_Interface_Output
 {
     /**
      * configured language
      *
      * @var String
      */
     private $_language;
     
     /**
      * configured template
      *
      * @var String
      */
     private $_template;
     
     /**
      * all available templates
      *
      * @var Array
      */
     private $_templates = array();
     
     /**
      * all available languages
      *
      * @var Array
      */
     private $_languages = array();
     
     /**
      * check for all extensions that are needed, initialize needed vars and read config.php
      */
     public function __construct()
     {
         parent::__construct();
         $this->_getTemplateList();
         $this->_getLanguageList();
     }
     
     /**
      * checking config.php setting for template, if not supportet set phpsysinfo.css as default
      * checking config.php setting for language, if not supported set en as default
      *
      * @return void
      */
     private function _checkTemplateLanguage()
     {
         $this->_template = trim(PSI_DEFAULT_TEMPLATE);
         if (!file_exists(APP_ROOT.'/templates/'.$this->_template.".css")) {
             $this->_template = 'phpsysinfo';
         }
         
         $this->_language = trim(PSI_DEFAULT_LANG);
         if (!file_exists(APP_ROOT.'/language/'.$this->_language.".xml")) {
             $this->_language = 'en';
         }
     }
     
     /**
      * get all available tamplates and store them in internal array
      *
      * @return void
      */
     private function _getTemplateList()
     {
         $dirlist = CommonFunctions::gdc(APP_ROOT.'/templates/');
         sort($dirlist);
         foreach ($dirlist as $file) {
             $tpl_ext = substr($file, strlen($file) - 4);
             $tpl_name = substr($file, 0, strlen($file) - 4);
             if ($tpl_ext === ".css") {
                 array_push($this->_templates, $tpl_name);
             }
         }
     }
     
     /**
      * get all available translations and store them in internal array
      *
      * @return void
      */
     private function _getLanguageList()
     {
         $dirlist = CommonFunctions::gdc(APP_ROOT.'/language/');
         sort($dirlist);
         foreach ($dirlist as $file) {
             $lang_ext = substr($file, strlen($file) - 4);
             $lang_name = substr($file, 0, strlen($file) - 4);
             if ($lang_ext == ".xml") {
                 array_push($this->_languages, $lang_name);
             }
         }
     }
     
     /**
      * render the page
      *
      * @return void
      */
     public function run()
     {
         $this->_checkTemplateLanguage();
         
         $tpl = new Template("/templates/html/index_dynamic.html");
         
         $tpl->set("template", $this->_template);
         $tpl->set("templates", $this->_templates);
         $tpl->set("language", $this->_language);
         $tpl->set("languages", $this->_languages);
         
         echo $tpl->fetch();
     }
 }
 
```