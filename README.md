###概述
    在侯斯特开放平台开发的一个DEMO应用“成语接龙”,
    基于ThinkPHP框架开发,
    用户可以通过关键词触发或退出应用,
    旨在帮助开发者快速上手理解并开发自己的应用，
    将自己的服务搬进微信。

###目录说明
    |-Application   示例目录
    |-index.php 项目入口文件
    |-Public    项目公共目录
    |-ThinkPHP  框架系统目录
    |-chengyu.sql 示例数据表，可直接部署

###安装与部署
    1.在侯斯特开放平台创建一个SAE应用
    2.将根目录下的chengyu.sql 导入到mysql数据库
    3.将项目部署到侯斯特开放平台自动创建的SAE代码仓库。
    （请先在侯斯特开放平台创建好应用项目，确保SAE各项服务正常开启（如memcache开启），
    然后将代码部署到SAE，建议手动部署，然后部署数据库。）
    4.在侯斯特开放平台配置应用事件
    “文本消息” text
    5.添加个人微信到“测试者白名单”
    6.如果部署正常，使用“测试者白名单”的个人微信号在“侯斯特开放平台”公众号
    输入文字“成语接龙”会得到相应的文字提示。


###非SAE环境部署
    1.请手动配置/Application/Conf/config.php 中有关数据库的配置
    2.将代码部署至个人服务器，并保证可以通过互联网访问
    3.在侯斯特开放平台创建一个普通应用，并将Url惨参数设为你的部署地址。
    4.其余操作与上一章节相同


###咨询与反馈
    在部署过程当中，有任何疑问可以直接提交issuse 或者发送邮件到 jinsong@weixinhost.com
    同时，可以在 http://open.weixinhost.com 点击下方“咨询反馈”按钮进行技术客服对话






