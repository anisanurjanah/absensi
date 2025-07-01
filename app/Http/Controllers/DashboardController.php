<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Count total employees (excluding admins)
        $totalEmployees = User::where('role', '!=', 'admin')->count();
        
        // Count total admins
        $totalAdmins = User::where('role', 'admin')->count();
        
        // Today's date
        $today = Carbon::today()->toDateString();
        
        // Count present employees today
        $presentToday = Attendance::where('date', $today)->distinct('user_id')->count('user_id');
        
        // Calculate absent employees
        $absentToday = $totalEmployees - $presentToday;
        
        // Count late check-ins
        // Assuming 09:00 is the standard time to check in
        $lateToday = Attendance::where('date', $today)
            ->whereTime('time_in', '>', '09:00:00')
            ->count();
            
        // Get department statistics
        $departmentStats = User::select('department', DB::raw('count(*) as total'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->get();
            
        // Get recent attendance activity (last 10)
        $recentActivity = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        // Get attendance summary for last 7 days
        $last7Days = [];
        $attendanceSummary = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $last7Days[] = $date->format('D');
            
            $count = Attendance::whereDate('date', $date->toDateString())->distinct('user_id')->count('user_id');
            $attendanceSummary[] = $count;
        }
        
        return view('pages.dashboard', [
            'type_menu' => 'home',
            'totalEmployees' => $totalEmployees,
            'totalAdmins' => $totalAdmins,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'lateToday' => $lateToday,
            'departmentStats' => $departmentStats,
            'recentActivity' => $recentActivity,
            'last7Days' => json_encode($last7Days),
            'attendanceSummary' => json_encode($attendanceSummary)
        ]);
    }
}
