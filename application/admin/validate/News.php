<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/30 0030
 * Time: 22:11
 */
namespace app\admin\validate;

class News extends BaseValidate{
    protected $rule=[
        'title'=>'require',
        'small_title'=>'require',
        'catid'=>'require|number',
        'description'=>'require',
        'content'=>'require'
    ];
    protected $message  =   [
        'title.require' => '标题不能为空',
        'small_title.require'     => '简略标题不能为空',
        'catid.require'   => '请选择分类栏目',
        'catid.number'   => '分类栏目必须是数字',
        'description.require'     => '文章摘要不能为空',
        'content.require'  => '文章内容不能为空'
    ];
}