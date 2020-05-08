<?php

namespace App\Admin\Controllers;

use App\Models\OrganizationPeople;
use App\Models\Organization;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrganizationPeopleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '机构人员';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrganizationPeople());

        $grid->column('id', __('Id'));
        $grid->column('organization_id', __('机构'))->using(Organization::GetKeyVall());
        $grid->column('user_id', __('姓名'))->using(User::GetKeyVall());
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));
        $grid->filter(function($filter){
            $filter->like('organization_id', 'organization_id');

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
        $show = new Show(OrganizationPeople::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('organization_id', __('机构'))->using(Organization::GetKeyVall());
        $show->field('user_id', __('姓名'))->using(User::GetKeyVall());
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
        $form = new Form(new OrganizationPeople());

        $form->select('organization_id', __('机构'))->options(Organization::GetKeyVall());
        $form->select('user_id', __('姓名'))->options(User::GetKeyVall());

        return $form;
    }
}
