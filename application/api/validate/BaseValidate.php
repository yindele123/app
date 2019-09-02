<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/28 0028
 * Time: 16:06
 */

namespace app\api\validate;
use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 检测所有客户端发来的参数是否符合验证类规则
     * 基类定义了很多自定义验证方法
     * 这些自定义验证方法其实，也可以直接调用
     * @throws ParameterException
     * @return true
     */
    public function goCheck()
    {
        //必须设置contetn-type:application/json
        $request = Request::instance();
        $params = $request->param();
        //$params['token'] = $request->header('token');

        if (!$this->check($params)) {
            $exception = new ParameterException(
                [
                    'msg' => is_array($this->error) ? implode(
                        ';', $this->error) : $this->error,
                ]);
            throw $exception;
        }
        return true;
    }
    protected function isPositiveInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }
}