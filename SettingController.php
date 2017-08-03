<?php

namespace App\Http\Controllers\Admin;


use App\Http\Model\Setting;
use Illuminate\Support\Facades\Config;
use Validator;
use Illuminate\Support\Facades\Input;

class SettingController extends CommonController
{
    //get.  admin/setting 全部配置項列表
    public function index()
    {
        $data = Setting::orderBy('sort', 'asc')->get();
        foreach ($data as $k => $v) {
            switch($v->field_type){
                case 'input':
                    $data[$k]->_html = '<input type="text" class ="size-6 form-control" name="content[]" value="'.$v->content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html = '<textarea type="text" class="form-control" name="content[]" cols="36" rows="3">'.$v->content.'</textarea>';
                    break;
                case 'radio':
                    //1|開啟, 0|關閉
                    $arr = explode(',', $v->field_value);
                    $str = '';
                    foreach($arr as $m => $n){
                        //1|開啟
                        $r = explode('|', $n);
                        $c = $v->content==$r[0]? ' checked ' : '';
                        $str .= '<input type="radio" name="content[]" value="'.$r[0].'"'.$c.'>' .$r[1].'　';
                    }
                    $data[$k]->_html = $str;
                    break;
            }
        }
        return view('admin.setting.index', compact('data'));
    }

    public function changeContent()
    {
        $input =  Input::all();

        foreach ($input['set_id'] as $k=>$v) {
            Setting::where('id', $v)->update(['content' => $input['content'][$k]]);
        }
        $this->putFile();
        return back()->with('msg', '網站設定更新成功');
    }

    public function putFile()
    {
//       echo \Illuminate\Support\Facades\Config::get('web.web_count'); 前台不需要這麼長
        $config =  Setting::pluck('content', 'name')->all();
        $path = base_path().'\config\web.php';
        $str = '<?php return '.var_export($config, true).';';

        file_put_contents($path, $str);
//       echo $path;
    }

    //get.  admin/setting/create 新增分類
    public function create()
    {
        return view('admin.setting.create');
    }

    //post.  admin/setting 新增配置項提交
    public function store()
    {
        $input = Input::except('_token');

        $rules =[
            'name' => 'required',
            'title' => 'required',
        ];

        $message =[
            'name.required' => '名稱不能為空',
            'title.required' => '標題不能為空',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){

            $re = Setting::create($input);
            if($re){
                return redirect('admin/setting');
            }else{
                return back()->with('errors','新增失敗，請稍後重試!');
            }

        }else{
            return back()->withErrors($validator);
        }

    }

    //get. admin/setting/{id}/edit 編輯配置項
    public function edit($id)
    {
        $field = Setting::find($id);

        return view('admin.setting.edit', compact('field'));
    }

    //put. admin/setting/{id} 更新配置項
    public function update($id)
    {
        $input = Input::except('_token', '_method');
        $re = Setting::where('id',$id)->update($input);
        if($re){
            $this->putFile();
            return redirect('admin/setting')->with('msg','修改成功!');
        }else{
            return back()->with('msg','修改失敗，請稍後重試!');
        }
    }

    //get. admin/setting/{id} 顯示單個分類訊息
    public function show()
    {

    }

    //get. admin/setting/{id} 刪除配置項
    public function destroy($id)
    {
        $re = Setting::where('id', $id)->delete();

        if($re){
            $this->putFile();
            $data = [
                'status' => 0,
                'msg'    =>'配置項刪除成功!',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'配置項刪除失敗，請稍後重試!',
            ];
        }
        return $data;
    }
}
