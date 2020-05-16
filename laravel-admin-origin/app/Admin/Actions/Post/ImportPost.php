<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Support\Helpers;


class ImportPost extends Action
{
    protected $selector = '.import-post';



    public function handle(Request $request)
    {

        $filePath = Helpers::uploadFile($request->file('file'), 'public',false,true);
        if(!$filePath){
            return $this->response()->error('文件扩展名不正确，只支持，csv，xls，xlsx')->refresh();
        }
        Excel::import(new UsersImport(), $filePath);

        return $this->response()->success('导入完成！')->refresh();
    }

    public function form()
    {
        $this->file('file', '请选择文件');
    }


    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-post">导入数据</a>
HTML;
    }
}
