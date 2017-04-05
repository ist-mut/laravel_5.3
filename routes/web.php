<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Database\Eloquent\Model;

class Department extends Model{
    //protected $table = 'DEPARTMENTS';
    protected $primaryKey = 'department_id';
    public $timestamps = false;
    protected $fillable = ['department_id','department_name','manager_id','location_id'];
    public function manager()
    {
        return $this->belongsTo('Employee','manager_id','employee_id');
    }

    public function location()
    {
        return $this->belongsTo('Location','location_id','location_id');
    }
    public function toTR(){
        $tmp = "<tr>";
        //ARRAY [DEPARTMENT_ID, DEPARTMENT_NAME, MANAGER_ID, LOCATION_ID]
        foreach($this->toArray() as $item){
            $tmp .= " <td>". ($item != null ? htmlentities($item, ENT_QUOTES): "&nbsp;"). "</td>\n";
        }
        $tmp .= "</tr>";
        /*<tr>
            <td>10</td>
            <td>Administration</td>
            <td>200</td>
            <td>1700</td>
        </tr>
        */
        return $tmp;
    }
    public function toTR_BAD(){
        $tmp = "<tr>";
        //ARRAY [DEPARTMENT_ID, DEPARTMENT_NAME, MANAGER_ID, LOCATION_ID]
        
        $tmp .= " <td>". $this->department_id. "<td>\n";
        $tmp .= " <td>". $this->department_name. "<td>\n";
        $tmp .= " <td>". $this->manager_id. "<td>\n";
        $tmp .= " <td>". $this->location. "<td>\n";
        
        $tmp .= "</tr>";
        /*<tr>
            <td>10</td>
            <td>Administration</td>
            <td>200</td>
            <td>1700</td>
        </tr>
        */
        return $tmp;
    }
    public static function toTable(){
        $departments = Department::all(); 
        $tmp = '<table border=1>';
        foreach($departments as $department){
            $tmp .=  $department->toTR();
        }
        $tmp .= '</table>';
        return $tmp;
    }
}



class Employee extends Model{
    //protected $table = 'EMPLOYEES';
    protected $primaryKey = 'employee_id';
    public $timestamps = false;
    public function department()
    {
        return $this->belongsTo('Department','department_id','department_id');
    }

    public function job()
    {
        return $this->belongsTo('Job','job_id','job_id');
    }

    public function history(){
        return $this->belongsToMany('Job','job_history', 'employee_id','job_id');
    }
    public function jobHistory()
    {
        return $this->hasMany('JobHistory','employee_id','employee_id');
    }
    public function toTR(){
        $tmp = "<tr>";
        foreach($this->toArray() as $item){
            $tmp .= " <td>". ($item != null ? htmlentities($item, ENT_QUOTES): "&nbsp;"). "</td>\n";
        }
        $tmp .= "</tr>";
        return $tmp;
    }
}

class Oracle extends Model{
    public function toTR(){
        $tmp = "<tr>";
        foreach($this->toArray() as $item){
            $tmp .= " <td>". ($item != null ? htmlentities($item, ENT_QUOTES): "&nbsp;"). "</td>\n";
        }
        $tmp .= "</tr>";
        return $tmp;
    }
}
class Region extends Oracle{
    protected $talble = 'REGIONS';
    protected $primaryKey = 'region_id';
    public $timestaps = false;
}

class Location extends Oracle{
    protected $talble = 'LOCATIONS';
    protected $primaryKey = 'region_id';
    public $timestaps = false;
    public function country()
    {
        return $this->belongsTo('Country','country_id','country_id');
    }
}

class Job extends Oracle{
    protected $table = 'JOBS';
    protected $primaryKey = 'job_id';
    public $timestaps = false;
    public $incrementing = false;
    public function employees(){
        return $this->belongsToMay('Employee','job_history','employee_id','job_id');
    }
}

class JobHistory extends Model{
    protected $table = 'JOB_HISTORY';
    protected $primaryKey = 'employee_id,job_id';
    public $timestaps = false;

    public function toTR(){
        $tmp = "<tr>";
        foreach($this->toArray() as $item){
            $tmp .= " <td>". ($item != null ? htmlentities($item, ENT_QUOTES): "&nbsp;"). "</td>\n";
        }
        $tmp .= "</tr>";
        return $tmp;
    }
}

class JobGrade extends Oracle{
    protected $table = 'JOB_GRADES';
    protected $primaryKey = 'GRADE_LEVEL';
    public $timestaps = false;
}
class Country extends Oracle{
    protected $table = 'COUNTRIES';
    protected $primaryKey = 'COUNTRY_ID';
    public $timestaps = false;
}
/*
REGIONS
LOCATIONS
DEPARTMENTS
JOBS
EMPLOYEES
JOB_HISTORY
JOB_GRADES
COUNTRIES*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/department', function(){
    //collection = array
    /*[
        Department(10,Administration,200,1700),
        Department(20,Marketing,201,1800),
        ...,
        ...,
      ]
    */
    /*$departments = Department::all(); 
    echo '<table border=1>';
    foreach($departments as $department){
        //echo $department->toTR();
        echo '<tr><td>'.$department->department_id.'</td>';
        echo '<td>'.$department->department_name.'</td>';
        echo '<td>'.$department->manager_id.'</td>';
        echo '<td>'.$department->location_id.'</td></tr>';
    }
    echo '</table>';*/
    echo Department::toTable();
});


Route::get('/dlist', function(){
    $departments = Department::all();

    foreach ($departments as $department) {
        echo $department->department_name;
    }
});


Route::get('/dget/{location}', function($location){
    $departments = Department::where('location_id',$location)->orderBy('department_id','desc')->take(10)->get();

    foreach ($departments as $department) {
        echo $department->department_name;
    }
});

Route::get('/dbad1700', function(){
    $departments = Department::all();
    $departments1700 = $departments->reject(function ($department) {
        return $department->department_name == 'Contracting';
    });
    foreach ($departments1700 as $department) {
        echo  $department->manager->first_name;
    }
});

Route::get('/dnew', function(){
    Department::create(['department_id'=>'200','manager_id'=>NULL,'department_name'=>'abc','location_id'=>1700]);
});


Route::get('/dupdate', function(){
    $department = Department::where(['department_id' => 200])->update(['department_name' => 'IST']);
});


Route::get('/ddel', function(){    
    Department::where(['department_id' => 200])->delete();
});

Route::get('/employee', function(){
    //collection = array
    /*[
        Department(10,Administration,200,1700),
        Department(20,Marketing,201,1800),
        ...,
        ...,
      ]
    */
    $emps = Employee::all(); 
    echo '<table border=1>';
    foreach($emps as $emp){
        echo $emp->toTR();
    }
    echo '</table>';
});

Route::get('/formSearch', function(){
    $token = csrf_token();
    echo "
    <form action='showSearchResult' method=post>
    <input type=text name='keyword'>
    <input type=submit value='search'>
    <input type='hidden' name='_token' value='$token'>
    </form>
    ";
});

Route::post('/showSearchResult', function(){
    
    $employees = Employee::where('FIRST_NAME',$_POST['keyword'])->get();
    echo '<table border=1>';
    foreach($employees as $employee){
        echo $employee->toTR();
    }
    echo '</table>';
});

Route::get('/employee/{id}', function($employee_id){
    $employee = Employee::find($employee_id);

    echo '<table border=1>';
        if($employee)
            echo $employee->toTR();
    echo '</table>';


    echo "<p>Employee department belongsTo</p>";
    echo '<table border=1>';
    if($employee)
        echo $employee->department->toTR();    
    echo '</table>';

    echo "<p>Employee job belongsTo</p>";
    echo '<table border=1>';
    if($employee)
        echo $employee->job->toTR();

    echo '</table>';

    echo "<p>Employee have jobHistory hasMany</p>";
    echo '<table border=1>';
    if($employee)
        foreach($employee->jobHistory as $jobHistory){
            //echo $jobHistory->toTR();
            echo "<tr><td>{$jobHistory->employee_id}</td><td>{$jobHistory->job_id}</td><td>{$jobHistory->start_date}</td><td>{$jobHistory->end_date}</td><td>{$jobHistory->department_id}</td></tr>";
        }
    echo '</table>';

    echo "<p>Employee have jobHistory belongstoMany</p>";
    echo '<table border=1>';
    if($employee)
        foreach($employee->history as $job){
            echo "<tr><td>{$job->job_id}</td><td>{$job->job_title}</td></tr>";
        }
    echo '</table>';
});

Route::get('xxx',function (){
    $employees = Employee::all();
    return view('employee')->with('employees',$employees);
});

Route::get('db', function(){
    $tables = DB::select('select table_name from tabs');

    echo '<table border=1>';
    foreach($tables as $table){
        echo "<tr><td>$table->table_name</td></tr>";
    }
    echo '</table>';
});

Route::get ( '/datatable', function () {
    $employee_data = Employee::all ();
    $country_data = Country::all ();
    $department_data = Department::all ();
    return view ( 'datatable/index')
        ->withEmployee ( $employee_data )
        ->withCountry( $country_data )
        ->withDepartment( $department_data );
});
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
Route::post ( '/editItem', function (Request $request) {
    
    $rules = array (
            'fname' => 'required|alpha',
            'lname' => 'required|alpha',
            'email' => 'required|email',
            'department' => 'required|numeric',
            'salary' => 'required|regex:/^\d*(\.\d{2})?$/' 
    );
    $validator = Validator::make ( Input::all (), $rules );
    if ($validator->fails ())
        return Response::json ( array (             
                'errors' => $validator->getMessageBag ()->toArray () 
        ) );
    else {
        
        $data = Employee::find ( $request->id );
        $data->first_name = ($request->fname);
        $data->last_name = ($request->lname);
        $data->email = ($request->email);
        $data->department_id = ($request->department);
        $data->salary = ($request->salary);
        $data->save ();
        return response ()->json ( $data );
    }
} );

Route::get('emp', function(){
    $data = Employee::all();
    echo '
<html>
<head>
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script>
    $(document).ready(function (){$("#example").DataTable();});
</script>
</head>
<body>
    <table id="example">
        <thead>
            <tr>
                <th>Name</th>
                <th>Last Name</th>
            </tr>
        </thead>
        <tbody>';
        foreach($data as $emp)
           echo "<tr><td>".$emp->first_name."</td><td>".$emp->last_name."</td></tr>";
            
        echo '</tbody>
    </table>
</body>
</html>';
});

