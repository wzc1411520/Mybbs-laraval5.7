<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TopicsController extends Controller
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
            ->description('description')
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
        $grid = new Grid(new Topic);
        $grid->disableCreateButton();
        $grid->disableExport();
//        $grid->actions(function ($actions) {
//
//            // append一个操作
//            $actions->append('<a href=""><i class="fa fa-eye"></i></a>');
//
//            // prepend一个操作
//            $actions->prepend('<a href=""><i class="fa fa-paper-plane"></i></a>');
//        });
        $grid->id('Id');
        $grid->column('Title')->display(function(){
            return "<a href='".route('topics.show', array_merge([$this->id, $this->slug]))."' target='_blank'>$this->title</a>";
        })->style('max-width:200px;word-break:break-all;');
//        $grid->excerpt('Excerpt');
//        $grid->body('Body');
        $grid->column('User')->display(function (){
            return  empty($this->user->avatar) ? 'N/A' : '<img src="'.storage_url($this->user->avatar).'" style="height:22px;width:22px"> ' . $this->user->name;
        });
        $grid->column('Category')->display(function (){
            return "<a href='".route('categories.show', $this->category->id)."' target='_blank'>".$this->category->name."</a>";
//            return $this->category->name;
        });
        $grid->column('Reply_count')->display(function (){
            return  $this->replies->count();
        });
        $grid->view_count('View count');
//        $grid->last_reply_user_id('Last replies user id');
//        $grid->order('Order');

//        $grid->slug('Slug');
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
        $show = new Show(Topic::findOrFail($id));

        $show->id('Id');
        $show->title('Title')->as(function (){
            return $this->title;
        });
        $show->body('Body')->unescape()->as(function ($body) {

            return $body;

        });;
        $show->user_id('User')->as(function(){
            return  $this->user->name;
        });
        $show->category_id('Category')->as(function (){
            return $this->category->name;
        });
        $show->reply_count('Reply count')->as(function (){
            return  $this->replies->count();
        });
        $show->view_count('View count');
//        $show->last_reply_user_id('Last replies user id');
//        $show->order('Order');
//        $show->excerpt('Excerpt');
//        $show->slug('Slug');
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
        $form = new Form(new Topic);

        $form->text('title', 'Title');
//        $form->textarea('body', 'Body');
        $form->simditor('body', 'Body');
//        $form->text('user_id','User')->value();
//        $form->number('category_id', 'Category id');
        $form->select('category_id','Category')->options(Category::all()->pluck('name','id'));
//        $form->number('reply_count', 'Reply count');
//        $form->number('view_count', 'View count');
//        $form->number('last_reply_user_id', 'Last replies user id');
//        $form->number('order', 'Order');
//        $form->textarea('excerpt', 'Excerpt');
        $form->text('slug', 'Slug')->placeholder('可自动填入');

        return $form;
    }
}
