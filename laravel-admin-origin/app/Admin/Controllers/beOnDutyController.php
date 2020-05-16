<?php

namespace App\Admin\Controllers;

use App\Models\beOnDuty;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class beOnDutyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '上下班时间设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new beOnDuty());

        $grid->column('on_duty', __('上班时间'));
        $grid->column('off_duty', __('下班时间'));

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
        $show = new Show(beOnDuty::findOrFail($id));

        $show->field('on_duty', __('上班时间'));
        $show->field('off_duty', __('下班时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new beOnDuty());

        $form->date('on_duty', __('上班时间'))->format('HH:mm:ss');
        $form->date('off_duty', __('下班时间'))->format('HH:mm:ss');

        return $form;
    }
}
