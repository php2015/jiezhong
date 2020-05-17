<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Content $content)
    {
        return redirect('/admin/users');

        /*
        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
        */
    }

    public function word()
    {
        $word = new PhpWord();
        $word->setDefaultFontName('仿宋');
        $word->setDefaultFontSize(16);
        $section = $word->createSection();
        //默认样式
        $section->addText('Hello PHP!');
        $section->addTextBreak();//换行符
        $section->addText(
          'Hello world',
          [
              'name' => '宋体',
              'size' => 20,
              'bold' => true
          ]
        );
        $section->addTextBreak(5); // 多个换行符
        $myStyle = 'myStyle';
        $word->addFontStyle(
            $myStyle,
            [
                'name' => 'Verdana',
                'size' => 12,
                'color' => '1BFF32',
                'bold' => true,
                'spaceAfter' => 20
            ]
        );

        $styleTable = [
            'borderColor' => '006699',
            'borderSize' => 6,
            'cellMargin' => 50
        ];
        $styleFirstRow = ['bgColor' => '66BBFF']; // 第一行样式
        $word->addTableStyle('myTable', $styleTable, $styleFirstRow);

        $table = $section->addTable('myTable');
        $table->addRow(400); // 行高
        $table->addCell(2000)->addText('学号');
        $table->addCell(2000)->addText('姓名');
        $table->addCell(2000)->addText('专业');
        $table->addRow(400);
        $table->addCell(2000)->addText('1');
        $table->addCell(2000)->addText('张三');
        $table->addCell(2000)->addText('a');
        $table->addRow(400);
        $table->addCell(2000)->addText('2');
        $table->addCell(2000)->addText('李四');
        $imageStyle = ['width' => 100, 'height' => 100, 'align' => 'center'];
        $table->addCell(2000)->addImage('https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1586523191&di=8299ec2cc2d5e22a145d97fab9bb45ea&src=http://a3.att.hudong.com/14/75/01300000164186121366756803686.jpg', $imageStyle);

        $section->addText('Hello laravel', $myStyle);
        $section->addPageBreak(); // 分隔符
        $writer = IOFactory::createWriter($word, 'Word2007');
        $writer->save('hessl.docx');
        return response()->download('hessl.docx');

    }

    public function map(Request $request)
    {
        $id = $request->input('type_id');
        $users = User::select('id','name')->where(['type_id'=>$id])->get();
        $names = array();
        $user_ids = '0';

        foreach ($users as $v){
            $user_ids = $user_ids.','.$v->id;
            $names[$v->id] = $v->name;
        }
        $sql = " SELECT
                    u.*
                FROM
                    ( SELECT * FROM locations where user_id in(".$user_ids.") GROUP BY time DESC ) AS u
                GROUP BY
                    u.user_id";
        $user_step = DB::select($sql);

        $labels = '';
        $labelStyles ='&labelStyles=';
        foreach ($user_step as $item){
            if($item->longitude && $item->latitude){
                $labels = $labels.$item->longitude.','.$item->latitude.'|';
                $labelStyles = $labelStyles.$names[$item->user_id].',1,14,0xffffff,0x000fff,1|';
            }
        }
        if($labels){
            $url = "//api.map.baidu.com/staticimage/v2?ak=ArMGvbtg1YXP8pVSHMNQ1dKGGUuYdfeK&width=1000&height=500&center=".getenv('APP_ADDRESS')."&zoom=12&labels=".$labels.$labelStyles;
            // $img = "<img style='margin:20px' width='1000' height='500'src='\".$url.\"'/>";
            //$urls = "//api.map.baidu.com/staticimage/v2?ak=ArMGvbtg1YXP8pVSHMNQ1dKGGUuYdfeK&width=1000&height=500&center=保定&zoom=12&labels=115.583022,38.924700|115.50,38.85|&labelStyles=张三,1,14,0xffffff,0x000fff,1|李四,1,14,0xffffff,0x000fff,1|";
            $img = "<img style=\"margin:20px\" width=\"1000\" height=\"500\" src=\"".$url."\"/>";
            //$img = "<img style=\"margin:20px\" width=\"1000\" height=\"500\" src=\"//api.map.baidu.com/staticimage/v2?ak=ArMGvbtg1YXP8pVSHMNQ1dKGGUuYdfeK&width=1000&height=500&center=保定&zoom=12&labels=115.583022,38.924700|115.50,38.85|&labelStyles=张三,1,14,0xffffff,0x000fff,1|李四,1,14,0xffffff,0x000fff,1|\"/>";
            echo $img;exit;
        }
        echo '暂无信息';
        //return redirect('/admin/types');

    }
}
