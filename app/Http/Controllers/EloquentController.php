<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Post;
use Datatables;
use Illuminate\Http\Request;

class EloquentController extends Controller {

	public function getBasic()
	{
		return view('datatables.eloquent.basic');
	}

	public function getBasicData()
	{
		$users = User::select(['id','name','email','created_at','updated_at']);

		return Datatables::of($users)->make();
	}

	public function getBasicObject()
	{
		return view('datatables.eloquent.basic-object');
	}

	public function getBasicObjectData()
	{
		$users = User::select(['id','name','email','created_at','updated_at']);

		return Datatables::of($users)->make(true);
	}

	public function getRelationships()
	{
		return view('datatables.eloquent.relationships');
	}

	public function getRelationshipsData()
	{
		$posts = Post::with('user')->select('*');

		return Datatables::of($posts)->make(true);
	}

	public function getJoins()
	{
		return view('datatables.eloquent.joins');
	}

	public function getJoinsData()
	{
		$posts = Post::join('users','posts.user_id', '=', 'users.id')
			->select(['posts.id', 'posts.title', 'users.name','users.email','posts.created_at','posts.updated_at']);

		return Datatables::of($posts)
			->editColumn('title', '{!! str_limit($title, 60) !!}')
			->editColumn('name', function($model) {
				return \HTML::mailto($model->email, $model->name);
			})
			->make(true);
	}

	public function getAddEditRemoveColumn()
	{
		return view('datatables.eloquent.add-edit-remove-column');
	}

	public function getAddEditRemoveColumnData()
	{
		$users = User::select(['id','name','email','password','created_at','updated_at']);

		return Datatables::of($users)
			->addColumn('action', function($user) {
				return '<a href="#" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
			})
			->editColumn('id', 'ID: {{$id}}')
			->removeColumn('password')
			->make(true);
	}

	public function getDtRow()
	{
		return view('datatables.eloquent.dt-row');
	}

	public function getDtRowData()
	{
		$users = User::select(['id','name','email','password','created_at','updated_at']);

		return Datatables::of($users)
			->addColumn('action', function($user) {
				return '<a href="#" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
			})
			->editColumn('id', '{{$id}}')
			->removeColumn('password')
			->setRowId('id')
			->setRowClass(function($user) {
				return $user->id % 2 == 0 ? 'alert-success' : 'alert-warning';
			})
			->setRowData([
				'id' => 'test',
			])
			->setRowAttr([
				'color' => 'red',
			])
			->make(true);
	}

	public function getCustomFilter()
	{
		return view('datatables.eloquent.custom-filter');
	}

	public function getCustomFilterData(Request $request)
	{
		$users = User::select(['id','name','email','created_at','updated_at']);

		return Datatables::of($users)
			->filter(function($query) use($request) {
				if ($request->has('name')) {
					$query->where('name', 'like', "%{$request->get('name')}%");
				}

				if ($request->has('email')) {
					$query->where('email', 'like', "%{$request->get('email')}%");
				}
			})
			->make(true);
	}

	public function getCarbon()
	{
		return view('datatables.eloquent.carbon');
	}

	public function getCarbonData()
	{
		$users = User::select(['id','name','email','created_at','updated_at']);

		return Datatables::of($users)
			->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
			->editColumn('updated_at', function($user) {
				return $user->updated_at->format('Y/m/d');
			})
			->make(true);
	}

}