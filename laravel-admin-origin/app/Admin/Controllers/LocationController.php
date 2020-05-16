<?php

namespace App\Admin\Controllers;

use App\Models\Location;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Type;

class LocationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '经纬度';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Location());

        $grid->model()->select('locations.id','name','longitude','latitude','time','site','title')
            ->leftJoin('users','users.id','locations.user_id')
            ->leftJoin('types','users.type_id','types.id')
            //if($created){
            // $grid->where(['steps.created_at'=>$created]) ;
            //}

            ->orderBy('time', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('name', __('姓名'));
        $grid->column('title', __('部门'));
        $grid->column('longitude', __('经度'));
        $grid->column('latitude', __('纬度'));

        $grid->column('site', __('地点'));

        $grid->column('time', __('创建时间'));
        //$grid->column('updated_at', __('更新时间'));
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('name','姓名');
            //$filter->date( 'steps.created_at','时间');
            $filter->between('time','时间')->datetime();
            $filter->equal('type_id','部门')->select(Type::GetKeyVall());



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
        $show = new Show(Location::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('longitude', __('Longitude'));
        $show->field('latitude', __('Latitude'));
        $show->field('site', __('Site'));
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
        $form = new Form(new Location());

        $form->number('user_id', __('User id'));
        $form->number('longitude', __('Longitude'));
        $form->number('latitude', __('Latitude'));
        $form->text('site', __('Site'));
        //$form->Field->Map("12122","11221","as");

        $form->latlong('1', '2123', '12');
        return $form;
    }
}
