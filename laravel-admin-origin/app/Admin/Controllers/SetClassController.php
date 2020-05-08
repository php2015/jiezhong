<?php

namespace App\Admin\Controllers;

use App\Models\SetClass;
use App\Models\Organization;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SetClassController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '机构排班';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SetClass());

        $grid->column('id', __('Id'));
        $grid->column('organization_id', __(' 机构'))->using(Organization::GetKeyVall());
        $grid->column('time', __('排班时间'));
        $grid->column('hour', __('时间段'));
        $grid->column('num', __('人数'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));
        $grid->filter(function($filter){
            $filter->like('organization_id', 'organization_id');
            $filter->equal('time')->date();

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
        $show = new Show(SetClass::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('organization_id', __('机构'))->using(Organization::GetKeyVall());
        $show->field('time', __('排班时间'));
        $show->field('hour', __('时间段'));
        $show->field('num', __('人数'));
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
        $form = new Form(new SetClass());

        $form->select('organization_id', __('机构'))->options(Organization::GetKeyVall());
        $form->date('time', __('排班时间'))->default(date('Y-m-d'));
        $form->text('hour', __('时间段'));
        $form->number('num', __('人数'));

        return $form;
    }
}
