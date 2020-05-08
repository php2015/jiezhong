<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员';

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
        //$grid->column('head', __('头像'));
        $grid->column('mobile', __('手机号'));
        //$grid->column('address', __('地址'));
        $grid->column('id_number', __('身份证号'));
        $grid->column('is_organization', __('是否机构人员'));
        $grid->column('created_at', __('创建时间'));
        $grid->disableActions();
        $grid->disableCreateButton();
        //$grid->column('updated_at', __('Updated at'));

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
        $show->field('name', __('Name'));
        $show->field('head', __('Head'));
        $show->field('mobile', __('Mobile'));
        $show->field('address', __('Address'));
        $show->field('open_id', __('Open id'));
        $show->field('id_number', __('Id number'));
        $show->field('is_organization', __('Is organization'));
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
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->text('head', __('Head'));
        $form->mobile('mobile', __('Mobile'));
        $form->text('address', __('Address'));
        $form->text('open_id', __('Open id'));
        $form->number('id_number', __('Id number'));
        $form->number('is_organization', __('Is organization'));

        return $form;
    }
}
