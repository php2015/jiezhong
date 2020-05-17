<?php

namespace App\Admin\Controllers;

use App\Models\Type;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Post\ImportPost;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '员工';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('name', __('姓名'));
        $grid->column('type_id', __('工种'))->using(Type::GetKeyVall());
        $grid->column('mobile', __('手机号'));
        //$grid->column('head_icon', __('头像'))->image();
        $grid->column('job', __('单位'));
        $grid->column('description', __('车间'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));
        $grid->tools(function ($tools) {
            $tools->append(new ImportPost());
        });
        $grid->filter(function($filter){
            $filter->in('type_id', "工种")->multipleSelect(Type::GetKeyVall());

        });
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('姓名'));
        $show->field('type_id', __('工种'))->using(Type::GetKeyVall());
        $show->field('mobile', __('手机号'));
        $show->field('head_icon', __('头像'))->image();
        $show->field('job', __('单位'));
        $show->field('description', __('车间'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('修改时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('name', __('姓名'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password= substr(md5($form->password),3,20);
            }
        });

        $form->text('mobile', __('手机号'));
        $form->password('password', __('密码'));
        $form->select('type_id', __('工种'))->options(Type::GetKeyVall());
        $form->image('head_icon', __('头像'));
        $form->text('job', __('单位'));
        $form->text('description', __('车间'));

        return $form;
    }
}
