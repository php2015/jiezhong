<?php

namespace App\Admin\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Organization;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;


class BookingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '预约';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Booking());

        $grid->column('id', __('Id'));
        //$grid->column('user_id', __('用户姓名'))->using(User::findUserByName($user_id));
        $grid->column('user_id', __('用户姓名'))->display(function ($user_id) {
            return User::where(['id' => $user_id])->first()->name ?? '';
        });
        $grid->column('children_id', __('儿童姓名'));
        /*
        $grid->column('user_id', __('儿童姓名'))->display(function ($children_id) {
            return Children::where(['id' => $children_id])->first()->name ?? '';
        });
        */
        $grid->column('organization_id', __('机构名称'))->using(Organization::GetKeyVall());
        $grid->column('time', __('预约时间'));
        $grid->column('hour', __(' 预约时段'));
        //$grid->column('wx_code', __('Wx code'));
        //$grid->column('wx_code', __('二维码'))->image('', 100, 100);
        $grid->column('is_del', __('是否删除'))->using(Booking::IsYes());
        $grid->column('is_ok', __('是否完成'))->using(Booking::IsYes());
        //$grid->column('created_at', __('Created at'));
        //$grid->column('updated_at', __('Updated at'));
        //$grid->disableActions();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            //$actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();

        });
        $grid->disableCreateButton();

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
        $show = new Show(Booking::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('用户姓名'))->as(function ($user_id) {
            return User::where(['id' => $user_id])->first()->name ?? '';
        });
        //$show->field('children_id', __('儿童姓名'));
        $show->field('children_id', __('儿童姓名'))->as(function ($children_id) {
            return User::where(['id' => $children_id])->first()->name ?? '';
        });
        $show->field('organization_id', __('机构名称'))->using(Organization::GetKeyVall());
        $show->field('time', __('预约时间'));
        $show->field('hour', __('预约时段'));
        $show->field('wx_code', __('二维码'))->image();
        $show->field('is_del', __('是否删除'))->using(Booking::IsYes());
        $show->field('is_ok', __('是否完成'))->using(Booking::IsYes());

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        /*
        $form = new Form(new Booking());

        $form->number('user_id', __('User id'));
        $form->number('children_id', __('Children id'));
        $form->number('organization_id', __('Organization id'));
        $form->date('time', __('Time'))->default(date('Y-m-d'));
        $form->number('hour', __('Hour'));
        $form->text('wx_code', __('Wx code'));
        $form->number('is_del', __('Is del'));
        $form->number('is_ok', __('Is ok'));

        return $form;
        */
    }
}
