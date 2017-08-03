<?php

namespace App\Http\Controllers\Admin;


use App\Http\Model\Post;
use App\Http\Model\PostCategory;
use Validator;
use Illuminate\Support\Facades\Input;
use Request;

class PostController extends CommonController
{
    public function __construct()
    {
        $this->cat = array('news');
        $this->catName = array(
            'news'        => '文章',
        );
        $this->page = Request::segment(2);//取得網址片段
    }

    //get.  admin/news 全部分類列表
    public function index()
    {
        $data = Post::where('cat', $this->page)->orderBy('id', 'desc')->paginate(10);
        $catName = $this->catName[$this->page];

        return view('admin.post.news.index', compact('data', 'catName'));
    }

    //get.  admin/news/create 新增分類
    public function create()
    {
        $category = (new PostCategory)->tree($this->page);
        $catName = $this->catName[$this->page];
        return view('admin.post.news.create', compact('category', 'catName'));
    }

    //post.  admin/news 新增文章提交
    public function store()
    {
        $input = Input::except('_token', 'file');
        $input['cat'] = $this->page;
        $input['pic'] = $this->upload();
        $input['created_date'] = time();

        $rules =[
            'name' => 'required',
            'content' => 'required',
        ];

        $message =[
            'name.required' => '文章標題不能為空',
            'content.required' => '文章內容不能為空',
        ];
        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            $re = Post::create($input);
            if($re){
                return redirect('admin/news');
            }else{
                return back()->with('msg','新增失敗，請稍後重試');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get. admin/news/{id}/edit 編輯文章
    public function edit($id)
    {
        $category = (new PostCategory)->tree($this->page);
        $catName = $this->catName[$this->page];
        $field = Post::find($id);

        return view('admin.post.news.edit', compact('category', 'field' ,'catName'));
    }

    //put. admin/news/{id} 更新文章
    public function update($id)
    {
        $input = Input::except('_token','_method', 'file');
        $pic = $this->upload();
        $upload = "";
        if($pic != 'fail'){
           $upload =  Post::where('id', $id)->update(['pic' => $pic]);
        }
        $re = Post::where('id', $id)->update($input);
        if($re || $upload){
            return redirect('admin/news')->with('msg', '文章修改成功！');
        }else{
            return back()->with('msg', '文章修改失敗，請稍後重試！');
        }

    }

    //get. admin/news/{id} 顯示單個分類訊息
    public function show()
    {

    }

    //get. admin/news/{id} 刪除單個文章
    public function destroy($id)
    {
        $re = Post::where('id', $id)->delete();
        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'文章刪除成功!',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'文章刪除失敗，請稍後重試!',
            ];
        }
        return $data;
    }
}
