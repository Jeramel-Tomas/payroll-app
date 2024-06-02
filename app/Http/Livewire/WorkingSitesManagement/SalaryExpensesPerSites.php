<?php

namespace App\Http\Livewire\WorkingSitesManagement;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\WorkingSite;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SalaryExpensesPerSites extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filterByMonth = '',
        $filterByMonthFormatted,
        $filterFrom,
        $filterTo;
    public $searchString;
    public $allSitesForDownload;

    public function clearFilter()
    {
        $this->reset();
    }

    public function downLoadSummary()
    {
        $data = [];
        $totalSalaries = 0;
        
        foreach ($this->allSitesForDownload as $siteKey => $site) {
            foreach ($this->getComputedSalaryPerSite() as $key => $value) {
                if ($site->id === $key) {
                    $totalSalaries += $value;
                    $data[$site->id] = [
                        'site_name' => $site->site_name,
                        'salary_expenses' => $value,
                    ];
                }
            }
        }

        $str = 'sitesalaryexpenses';
        $fileName = $str.Carbon::now() . '.pdf';
        $pdfContent = PDF::loadView('working-sites-management.download-pages.siteSalaryExpensesDownload', [
            'data' => $data,
            'date_filtered' => $this->filterByMonth,
            'total_salaries' => $totalSalaries,
        ])->output();
        
        return response()->streamDownload(
            fn () => print($pdfContent),
            $fileName
        );
    }

    public function render()
    {
        // get all sites
        $allSites = WorkingSite::orderBy('site_name');

        if ($this->filterByMonth) {
            $this->filterByMonthFormatted = Carbon::create($this->filterByMonth)->format('F');
            $this->filterFrom = Carbon::create($this->filterByMonth)->startOfMonth();
            $this->filterTo = Carbon::create($this->filterByMonth)->endOfMonth();
        }

        if ($this->searchString) {
            $allSites->where('site_name', 'like', '%'. $this->searchString .'%');
        }

        $sites = $allSites->get();
        $this->allSitesForDownload = $sites;

        return view('livewire.working-sites-management.salary-expenses-per-sites', [
            'sites' => $sites,
            'totalSalaries' => $this->getComputedSalaryPerSite(),
        ]);
    }

    /*  get employee per site */
    private function getComputedSalaryPerSite()
    {
        $allSites = DB::table('employee_working_sites')
            ->join('working_sites', 'working_sites.id', '=', 'employee_working_sites.working_site_id')
            ->get();

        $salaryPerSite = [];
        $grossIncome = 0;
        $daysRateComputed = 0;
        $otComputed = 0;
        $ot = 0;
        $daysAttendance = 0;
        foreach ($allSites as $key => $allSite) {
            $empTimeRecAndRate = $this->getEmployeeTimeRecords($allSite->employee_information_id, $allSite->working_site_id);
            $jobRate = (float)($allSite->job_title_rate ?? 0);
            $daysAttendance = (float)($empTimeRecAndRate->days_present ?? 0);
            $ot = $empTimeRecAndRate->total_ot ?? 0;

            (float)$otComputed = (float)($jobRate / 8) * (float)($ot);
            (float)$daysRateComputed = $jobRate * $daysAttendance;
            (float)$grossIncome = $daysRateComputed + $otComputed;

            $salaryPerSite[$allSite->working_site_id][$allSite->employee_information_id] = $grossIncome;
        }

        $totalSalaryPerSite = [];
        foreach ($salaryPerSite as $key => $value) {
            $totalSalaryPerSite[$key] = $this->computeSumPerSite($value);
        }

        return $totalSalaryPerSite;
    }

    /**
     * get the sum per site
     */
    private function computeSumPerSite($salaries = [])
    {
        if (empty($salaries)) {
            return 0;
        }

        $sum = 0;
        foreach ($salaries as $key => $salary) {
            $sum += $salary;
        }

        return (double)$sum;
    }
    /* get employee time record */
    private function getEmployeeTimeRecords($empid, $siteId)
    {
        // employee_time_records
        if (!$empid && !$siteId) {
            return null;
        }
        return DB::table('employee_time_records')
        ->where([
            'employee_id' => $empid,
            'site_id' => $siteId,
        ])
        ->whereBetween(DB::raw('DATE(attendance_from)'), [
            $this->filterFrom ? $this->filterFrom : Carbon::now()->startOfMonth(),
            $this->filterTo ? $this->filterTo : Carbon::now()->endOfMonth(),
        ])
        ->first();
    }
}
