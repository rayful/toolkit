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

##### 关于在Model里面对每个属性的声明
    @var:
    /**
     * Model类在PHPDoc里面对这个属性的类型的声明
     * 使用场景:editor,shower,validate中的autoConvert()
     * @var string
     * @example string int bool float array string[] MongoDate MongoId ...
     */

  @name:
    /**
     * Model类在PHPDoc里面对这个属性的标签的声明
     * 使用场景:editor,shower时必选
     * @var string
     * @example 姓名/性别/标题
     */

  @input:
    /**
     * editor中使用,声明这个属性的输入框类型
     * 使用场景:editor 可选 如果不声明类型，程序还将能自动根据声明的var类型判断
     * @var string
     * @example select/checkbox/radio/textarea
     */

  @ignore:
    /**
     * editor中使用，声明这个属性是否在某些编辑场景中被忽略
     * 使用场景:editor 可选 默认每个字段都会输出到编辑器中,除非声明ignore
     * @var string
     * @example edit/show
     */

  @tips:
    /**
     * editor中使用,声明这个属性在输入框后面的提示
     * 使用场景:editor 可选 如果需要在输入框后面增加提示，请声明这个类型
     * @var string
     */
