<?php

namespace App\Admin\Controllers;

use App\Models\GameRecordScore;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use function Sodium\increment;

class LeaderboardController extends Controller
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


        $grid = new Grid(new GamerecordScore);
        $grid->rows(function (Grid\Row $row) {
            $row->column('position', ($row->number+1));
        });
        $grid->position();


//        $grid->id('Id');
        $grid->users()->username();
        $grid->users()->name();
//        $grid->user_id('User id');
//        $grid->level_id('Level id');
        $grid->score('Score');
        $grid->created_at('Created at');

        $grid->column('Show Detail')->display(function(){
           return '<a class="btn btn-primary btn-xs" href="/admin/users/' . $this->user_id . '"><i class="fa fa-eye"> SHOW DETAIL</i></a>';
        });

//        $grid->updated_at('Updated at');
//        $grid->session_id('Session id');
        $grid->model()->where('level_id',8);
        $grid->model()->orderBy('score','desc');
        $grid->model()->join('users','users.id','game_record_scores.id')->where('users.is_banned',0);
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
        $show = new Show(GamerecordScore::findOrFail($id));

        $show->id('Id');
        $show->user_id('User id');
        $show->level_id('Level id');
        $show->score('Score');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        $show->session_id('Session id');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GamerecordScore);

        $form->number('user_id', 'User id');
        $form->number('level_id', 'Level id');
        $form->number('score', 'Score');
        $form->number('session_id', 'Session id');

        return $form;
    }
}
