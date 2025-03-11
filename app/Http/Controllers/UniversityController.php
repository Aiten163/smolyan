<?php
namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Department;
use App\Models\Employee;
class UniversityController extends Controller
{
    // Отображение факультетов
    public function index()
    {
        $faculties = Faculty::all();
        return view('data_base.lab3.index', compact('faculties'));
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
        $employees = Employee::where('department_id', $departmentId)->get();
        return response()->json($employees);
    }
}
