<?php

namespace App\Http\Controllers\Admin;


use App\Http\Model\PostCategory;
use Illuminate\Support\Facades\Input;
use \Validator;
use Request;

class PostCategoryController extends CommonController
{
    public function __construct()
    {
        $this->cat = array('news');
        $this->catName = array(
          'news'        => '分類',
        );
        $this->page = Request::segment(2);//取得網址片段
    }

    //get.  admin/news/category 全部分類列表
    public function index()
    {
        if(in_array($this->page, $this->cat)){
            $categorys = (new PostCategory)->tree($this->page);
            return view('admin.post_category.news.index')->with('data', $categorys);
        }else{
            return redirect('admin/index')->with('msg', '查無此分類');
        }

    }

    //get.  admin/category/news/create 新增分類
    public function create()
    {
        $data = PostCategory::where('cat', $this->page)->where('pid' , 0)->get();
        $catName = $this->catName[$this->page];
        return view('admin.post_category.news.create', compact('data','catName'));
    }

    //post.  admin/news/category 新增分類提交
    public function store()
    {
        $input = Input::except('_token');
        $input['cat'] = $this->page;

        $rules=[
            'name' => 'required'
        ];

        $message = [
            'name.required' => '分類名稱不能為空'
        ];

        $validator = Validator::make($input, $rules, $message);

        if($validator->passes()){

            $re = PostCategory::create($input);
            if($re){
                return redirect('admin/news/category');
            }else{
                return back()->with('msg', '新增失敗，請稍後重試!');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get. admin/news/category/{category}/edit 編輯分類
    public function edit($id)
    {
        $field = PostCategory::find($id);
        $data = PostCategory::where('cat', $this->page)->where('pid' , 0)->get();
        $catName = $this->catName[$this->page];

        return view('admin.post_category.news.edit', compact('field', 'data', 'catName'));
    }

    //put. admin/news/category/{category} 更新分類
    public function update($id)
    {
        $input = Input::except('_token', '_method');
        $re = PostCategory::where('id', $id)->update($input);
        if($re){
            return redirect('admin/news/category')->with('msg', '修改成功');
        }else{
            return back()->with('msg','修改失敗，請稍後重試!');
        }
    }

    //get. admin/news/category/{category} 顯示單個分類訊息
    public function show()
    {

    }

    //get. admin/news/category/{category} 刪除單個分類
    public function destroy($id)
    {

//        PostCategory::where('pid', $id)->update(['pid'=>0]);//有子分類情況，把子分類變成主分類
        $pc = PostCategory::where('pid', $id)->get();
        if(count($pc)>0){
            $data = [
                'status' => 3,
                'msg'    =>'有子分類無法刪除!',
            ];
        }else{
            $re = PostCategory::where('id', $id)->delete();

            if($re){
                $data = [
                    'status' => 0,
                    'msg'    => '分類刪除成功!'
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg'    => '分類刪除失敗，請稍後重試!'
                ];
            }
        }
        return $data;
    }
}
