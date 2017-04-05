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

Class Employee extends Model{
    protected$talble = 'Employees'; 
    protected $primaryKey = 'department_id'; 
    public $timestamps = false;
    public function toTR(){
        $tmp = "<tr>";
        foreach($this->toArray() as $item){
            $tmp .= " <td>". ($item != null ? htmlentities($item, ENT_QUOTES): "&nbsp;"). "</td>\n";
        }
        $tmp .= "</tr>";
        return $tmp;
    }
    public function toTH(){
        $tmp = "<tr>";
        foreach($this->getAttributes() as $key => $item){
            $tmp .= " <th>". ($item != null ? htmlentities($key, ENT_QUOTES): "&nbsp;"). "</th>\n";
        }
        $tmp .= "</tr>";
        return $tmp;
    }
}

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\Schema;
Route::get('emp', function (){
    $data = Employee::all();
    echo '<html>';
    echo '<head>';
    echo '<script src="//code.jquery.com/jquery-1.12.4.js"></script>';
    echo '<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>';
    echo '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">';
    echo '<script>';
    echo '$(document).ready(function() {
        $("#example").DataTable();
    } );';
    echo '</script>';
    echo '</head>';
    echo '<body>';
    echo '<table id="example">';
    echo '<thead>';
    echo $data->first()->toTH();
    echo '</thead>';
    echo '<tbody>';
    foreach($data as $emp){
        echo $emp->toTR();
    }
    echo '</tbody>';    
    echo '</table>';
    echo '</body>';
    echo '</html>';
});