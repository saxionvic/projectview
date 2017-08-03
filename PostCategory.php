<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_category';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = []; //排除輸入的字段 / fillable 只有輸入的字段可以通過

    public function tree($cat)
    {
        $categorys = $this->where('cat', $cat)->orderBy('sort', 'asc')->get();
        return $this->getTree($categorys, 'name', 'id', 'pid');
    }

    public function getTree($data, $field_name, $field_id = 'id', $field_pid = 'pid', $pid=0)
    {
        $arr = array();
        foreach ($data as $k => $v){
            if($v->$field_pid == $pid){
                $data[$k]['_'.$field_name] = $data[$k][$field_name];
                $arr[]=$data[$k];
                foreach ($data as $m => $n){
                    if($n->$field_pid == $v->$field_id){
                        $data[$m]['_'.$field_name] = '　├ '.$data[$m][$field_name];
                        $arr[] = $data[$m];
                    }
                }
            }
        }
        return $arr;
    }
}


