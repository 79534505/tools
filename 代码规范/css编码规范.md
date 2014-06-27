#CSS编码规范

为提高页面代码质量、工作效率以及网站后期的可维护性，改善用户体验，充分发挥团队协作的潜能，而制定以下CSS规范，希望能通过此规范的制作，使网站前端工作从效果图设计到静态页制作逐步走向产品化、模块化的专业制作体系，也使网站更好的发挥兼容性，以增强各种浏览器的用户体验，提高页面代码的可读性。更好的配合程序开发，从而节约一定的网站开发时间。

##目录

* [CSS结构](#css)
* [CSS文件命名](#css-1)
* [CSS书写要求](#css-2)
* [CSS样式命名](#css-3)

## CSS结构

### base.css 
> 每个页面必须加载，对页主要是对浏览器的一些默认样式进行CSS Reset。

### common.css
> 各种模型库，主要定义各种通用样式，如几种不同尺寸的头像、页面、等常用模型，每个前端都可以根据需求进行添加新的模块，但必须告知所有前端，确认以后方可添加，原则上不进行删除模块操作，为了方便之间模块化和提高弹性，正常情况下，为避免外边界冲突，组件不设置外边界。

### page.css
> 每个页面有一个或每个专题共用一个，定义当前页面独立的样式，一个page.css通常一个人维护，如果多人维护，需要添加注释。

## CSS文件命名
> CSS文件命名要求尽量使用相对应的专题/功能的英文名称，如 个人网盘页面的css可以命名为 netdisk.css

## CSS书写要求
### 1. 属性需要写在一行。需要在“{"和"}”前后加空格。
  ```css
    .selector { property:value;property:value; }
  ```

### 2.多个(>2)selector每个占一行：
  ```css
    .selector1,
    .selector2,
    .selector3 { property:value;property:value; }
  ```

### 3.兼容多个浏览器时，将标准属性写在后面，如：
  ```css
    -webkit-border-radius:4px;-moz-border-radius:4px;border-radius: 4px;
  ```
  
### 4.注释

    注释的格式:
      /* mod: doulist S*/
      ……
      /* mod: doulist E*/

### 5.注意事项
#### 5.1.尽量避免使用filter
#### 5.2.不要直接修改标签的样式
```css
  div { ... } /*不要使用*/
```
#### 5.3.不要在标签上直接写样式
```html
  <div style="margin-bottom:30px;"> <!--不要使用-->
```
#### 5.4.不要在CSS中使用 expression

#### 5.5.不要在CSS中使用 @import 

#### 5.6.如非特殊格式要求，不要在CSS中使用 !important 

#### 5.7.如非特殊格式要求，绝对不要在CSS中使用 "*" 选择符

#### 5.8.避免使用如下低效的选择器
```css
  body > * {...}    /*不要使用*/
  ul > li > a {...}   /*不要使用*/
  #footer > h3 {...}    /*不要使用*/
  ul#top_blue_nav {...}   /*不要使用*/
```
#### 5.9.尽量避免使用CSS Hack
> 如需用到推荐使用下面的：

> ###### IE6 : _property:value

> ###### IE6/7 : *property:value

> ###### IE6/7/8/9 : property:value\9

> ###### not IE6 : property//:value

## CSS样式命名

### 命名尽量使用相应的英文名称或相应的英文缩写如
> .title (标题)

> .sidebar (边栏)

> .search （搜索）

> 非共用模块的css请加当前页面的名字或缩写以免冲突，如好友页面：

> .friend_top （好友页面的头部部分）

> .friend_search （好友页面的搜索部分）

### 名称请用下划线“_”分割，所有英文字母使用小写。如：
> .friend_l_title

### 对于表示方位、大小的形容词可以使用缩写，如：
> 如left可写成l，right可写成r，big可写成b等等。

### 推荐CSS命名
> .container 	容器 

> .header 	页头部分 

> .nav 	导航 

> .subNav	二级导航

> .menu 	菜单 

> .submenu 	子菜单 

> .main 	页面主体 

> .column	栏目

> .title	标题

> .content 	内容部分 

> .footer 	页脚部分 

> .icon	小图标

> .tag 	标签 

> .tab	选项卡

> .list	文章列表

> .note	注释

> .hot	热点

> .download	下载

> .msg或 .message 	提示信息 

> .tips 	小技巧 

> .vote 	投票 

> .friendlink 	友情连接 

> .summary 	摘要 

> .search 	搜索 

> .btn	按钮

> .copyright 	版权信息 

> .branding 	商标 

> .brandingLogo 	LOGO 

> .banner	广告

> .siteinfo 	网站信息 

> .siteinfoLegal 	法律声明 

> .siteinfoCredits 	信誉 

> .joinus 	加入我们 

> .partner 	合作伙伴 

> .service 	服务 

> .regsiter 	注册 

> .login	登陆

> .status 	状态 

### 推荐CSS名称简写索引表
> 全拼  缩写	说明

> left	  l   左边

> right	  r	  右边

> center	c	  中间

> big 	  b	  大的

> small	  s	  小的

