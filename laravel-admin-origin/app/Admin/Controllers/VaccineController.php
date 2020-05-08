<?php

namespace App\Admin\Controllers;

use App\Models\Vaccine;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VaccineController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '疫苗';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Vaccine());

        $grid->column('id', __('Id'));
        $grid->column('title', __('名称'));
        $grid->column('age', __('年龄'));
        $grid->column('num', __('数量'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));

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
        $show = new Show(Vaccine::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('名称'));
        $show->field('age', __('年龄'));
        $show->field('num', __('数量'));
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
        $form = new Form(new Vaccine());

        $form->text('title', __('名称'));
        $form->number('age', __('年龄'));
        $form->number('num', __('数量'));

        return $form;
    }
}
