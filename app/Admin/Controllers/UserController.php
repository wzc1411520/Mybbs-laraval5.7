<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('用户管理')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);
        //禁止新增按钮
        $grid->disableCreateButton();
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('name', 'Name');

        });
        $grid->id('Id');
        $grid->column('avatar')->display(function ($avatar){
            return "<img src='".storage_url($avatar)."' alt='avatar' width='50'>";
        });
        $grid->name('Name');
        $grid->email('Email');
        $grid->introduction('Introduction');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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

        $show->panel()
            ->style('info')
            ->title('基本信息...')->tools(function ($tools) {
//                $tools->disableEdit();
//                $tools->disableList();
//                $tools->disableDelete();
            });
        $show->avatar()->unescape()->as(function ($avatar) {

            return "<img src='".storage_url($avatar)."}' width='150'/>";

        });
        $show->name('Name');
        $show->email('Email');
        $show->introduction('Introduction');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);
//        $form->tab('Basic info', function ($form) {
//
//            $form->text('username');
//            $form->email('email');
//
//        })->tab('Profile', function ($form) {
//
//            $form->image('avatar');
//            $form->text('address');
//            $form->mobile('phone');
//
//        });
        $form->image('avatar','Avatar');
//        die;
        $form->email('email', 'Email');
        $form->password('password','Password');
        $form->text('name', 'Name');
        $form->text('introduction', 'Introduction');
        $form->display('created_at');
        $form->display('updated_at');

        return $form;
    }
}
