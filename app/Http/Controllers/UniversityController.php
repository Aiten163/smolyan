<?php
namespace App\Http\Controllers;

use App\Models\Employee_details;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Employee;
class UniversityController extends Controller
{
    // Отображение факультетов
    public function index()
    {
        $faculties = Faculty::all();
        return view('data_base.lab3_4.index', compact('faculties'));
    }

    // Получение кафедр по выбранному факультету
    public function getDepartments($facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)->get();
        return response()->json($departments);
    }

    // Получение сотрудников по выбранной кафедре
    public function getEmployees($departmentId)
    {
        $employees = Employee::where('department_id', $departmentId)
        ->select('id', 'full_name', 'position', 'salary_rate')
        ->get();
        return response()->json($employees);
    }
    public function showEmployees($employeeId)
    {
        $employ = Employee::find($employeeId);
        $employ_info = Employee_details::find($employeeId);
        $employ = collect($employ->toArray())->merge($employ_info->toArray());
        return view('data_base.lab3_4.employ', compact('employ'));
    }
}
