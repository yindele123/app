<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 11:55
 */
namespace app\api\model;
class ProductProperty extends BaseModel
{
    protected $hidden=['product_id', 'delete_time', 'id'];
}