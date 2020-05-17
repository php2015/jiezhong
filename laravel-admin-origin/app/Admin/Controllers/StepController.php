<?php

namespace App\Admin\Controllers;

use App\Models\Step;
use App\Models\Type;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;



class StepController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '步数';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid( )
    {

        $grid = new Grid(new Step());
       // $created =  empty()$_GET['created_at'];

        $grid->model()->select('steps.id','name','set_num','user_num','time','num','title')
            ->leftJoin('users','users.id','steps.user_id')
            ->leftJoin('types','users.type_id','types.id')
            //if($created){
               // $grid->where(['steps.created_at'=>$created]) ;
            //}

         ->orderBy('steps.user_num', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('name', __('姓名'));
        $grid->column('title', __('工种'));
        $grid->column('num', __('部门步数'));
        $grid->column('set_num', __('目标步数'));

        $grid->column('user_num', __('步数'));

        $grid->column('time', __('创建时间'));
        //$grid->column('updated_at', __('更新时间'));
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            //$filter->date( 'steps.created_at','时间');
            $filter->between('time','时间')->datetime();
            $filter->equal('type_id','工种')->select(Type::GetKeyVall());


        });

        $grid->disableActions();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Step::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Step());



        return $form;
    }
}
