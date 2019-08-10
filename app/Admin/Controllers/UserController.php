<?php

namespace App\Admin\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
        $grid = new Grid(new User);

        $grid->id('Id');
//        $grid->role_id('Role id');
        $grid->name('Name');
        $grid->username('Username');
//        $grid->email('Email');
//        $grid->avatar('Avatar');
//        $grid->password('Password');
//        $grid->remember_token('Remember token');
//        $grid->settings('Settings');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
//        $grid->access_token('Access token');
//        $grid->no_hp('No hp');
//        $grid->location_id('Location id');
//        $grid->gender('Gender');
        $grid->column('Status Player')->display(function(){
            if($this->is_banned == 0)
            {
                return '<span class="label label-success">Active</span>';
            }else{
                return '<span class="label label-danger">Banned</span>';
            }
        });
        $grid->column('Ban User')->display(function(){
            if($this->is_banned == 0) {
                return '<a class="btn btn-danger btn-xs" href="/admin/users/ban/' . $this->id . '"><i class="fa fa-eye"> BAN USER</i></a>';
            }else{
                return '<a class="btn btn-primary btn-xs" href="/admin/users/activate/' . $this->id . '"><i class="fa fa-eye"> ACTIVATE USER</i></a>';
            }
        });

        $grid->column('Action')->display(function(){
            return '<a href="/admin/users/'.$this->id.'"><i class="fa fa-eye"> Show User Detail </i></a>';
        });
        $grid->filter(function($filter){
            $filter->scope('banned user')->where('is_banned',1);
            $filter->scope('active user')->where('is_banned',0);
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
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
//        $show->role_id('Role id');
        $show->name('Name');
//        $show->email('Email');
//        $show->avatar('Avatar');
//        $show->password('Password');
//        $show->remember_token('Remember token');
//        $show->settings('Settings');
//        $show->created_at('Created at');
//        $show->updated_at('Updated at');
        $show->username('Username');
//        $show->access_token('Access token');
//        $show->no_hp('No hp');
//        $show->location_id('Location id');
//        $show->gender('Gender');

        $show->Statistic('Statistic Information',function($Statistic){

            $Statistic->Statistic()->name();
            $Statistic->value();

        });

        $show->BestScore('Best Score',function($BestScore){
            $BestScore->score();
            $BestScore->level_id();
            $BestScore->created_at();

        });

        $show->GameSessions('History',function($GameSessions){
            $GameSessions->score();
            $GameSessions->level_id();
            $GameSessions->created_at();
        });

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

//        $form->number('role_id', 'Role id');
        $form->text('name', 'Name');
//        $form->email('email', 'Email');
//        $form->image('avatar', 'Avatar')->default('users/default.png');
//        $form->password('password', 'Password');
//        $form->text('remember_token', 'Remember token');
//        $form->textarea('settings', 'Settings');
        $form->text('username', 'Username');
//        $form->text('access_token', 'Access token');
//        $form->text('no_hp', 'No hp');
//        $form->number('location_id', 'Location id');
//        $form->switch('gender', 'Gender')->default(1);


        return $form;
    }

    public function ban(User $user){

        $user->is_banned = 1;
        $user->save();
        return redirect()->back();
    }

    public function activate(User $user){
        $user->is_banned = 0;
        $user->save();
        return redirect()->back();
    }
}
