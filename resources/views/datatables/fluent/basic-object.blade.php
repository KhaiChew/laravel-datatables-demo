@extends('datatables.template')

@section('demo')
<div class="row">
    <div class="col-md-12">
        <table id="users-table" class="table table-condensed">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('controller')
    public function getBasicObject()
    {
        return view('datatables.fluent.basic-object');
    }

    public function getBasicObjectData()
    {
        $users = DB::table('users')->select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)->make(true);
    }
@endsection

@section('js')
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url("fluent/basic-object-data") }}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'}
        ]
    });
@endsection