<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;


class ImportPost extends Action
{
    protected $selector = '.import-post';

    public function script()
    {
        return <<<EOT
   $('.file-upload').on('change', function () {
        $('.file-upload-form').submit();
    });
EOT;
    }

    public function handle(Request $request)
    {
        Excel::import(new EnterpriseImport, $request->file('file'));
        return $this->response()->success('Success message...')->refresh();
    }

    public function form()
    {
        $this->file('file', 'Please select file')
            ->options(['showPreview' => false,
                'allowedFileExtensions'=>['xlsx'],
                'showUpload'=>true
            ]);
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-post">导入数据</a>
HTML;
    }
}
