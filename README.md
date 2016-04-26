# toolkit
睿锋公司开源网络框架及软件包内常用的工具包。

### 安装
composer require rayful/toolkit

### 使用 
##### trait
- validate: 验证及自动转换类型
- editor: 根据PHPdoc里面定义的类型自动生成数据编辑器
- shower: 根据PHPdoc里面定义的类型自动生成数据展示器
- objectTool: 包含遍历对象及获得当前对象公开属性的等四个基础方法，多个包里面会用到。

##### 类
- Date: 常用的MongoDate类型的展示类，包含::toString等几个常用方法
- StringTool: 常用的字符串操作类，包括toMongoId等数个字符串方法

