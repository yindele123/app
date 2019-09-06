<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/6 0006
 * Time: 13:21
 */
namespace app\admin\validate;
class Version extends BaseValidate{
    protected $rule=[
        'app_type'=>'require|number',
        'version'=>'require|number',
        'version_code'=>'require',
        'apk_url'=>'require|url',
        'upgrade_point'=>'require'
    ];
    protected $message  =   [
        'title.require' => '请选择app类型',
        'version.require'     => '版本号不能为空',
        'version.number'     => '版本号必须是数字',
        'version_code.require'   => '对外版本号不能为空',
        'apk_url.require'   => 'apk最新地址不能为空',
        'apk_url.url'   => '请输入正确的URL',
        'upgrade_point.require'     => '升级提示不能为空',
    ];
}