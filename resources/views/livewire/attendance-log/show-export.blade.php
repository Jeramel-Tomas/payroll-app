<div>
    {{-- <h1>testing the modal</h1> --}}
    <div wire:ignore.self class="modal fade" data-bs-backdrop='static' tabindex="-1" id="myModal" role="dialog">
        <div class="modal-dialog">
        {{-- <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$modalTitle}}</h5>
                </div>
                <div class="modal-body">
                    <p>Select to export</p>                    
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" wire:model="typetoExport" value="allEmployee" class="form-check-input"> All employees
                        </label>
                    </div>
                    @if ($typetoExport && $typetoExport === $allEmployee)
                        <div class="ps-4">
                            <div class="ps-4">
                                <div class="form-check">
                                    <input type="radio" wire:model="timeRecordType" value="dailyTimeRecord" class="form-check-input"> Daily
                                </div>
                                @if ($timeRecordType && $timeRecordType === $dailyTimeRecord)
                                    <div class="ps-4">
                                        <label for="" class="form-label">Select a day</label>
                                        <div class="col-8">
                                            <input type="date" class="form-control" name="datepicker" id="datepicker" wire:model="selectedDateForDTR" />
                                        </div>
                                        {{-- {{$selectedDateForDTR}} --}}
                                    </div>
                                @endif
                                <div class="form-check">
                                    <input type="radio" wire:model="timeRecordType" value="halfMonth" class="form-check-input"> A half Month
                                </div>
                                @if ($timeRecordType && $timeRecordType === $halfMonth)
                                    <div class="ps-4">
                                        <div class="form-check">
                                            <input type="radio" wire:model="halfMonthType" value="firstHalf" class="form-check-input"> First half of the Month
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" wire:model="halfMonthType" value="secondHalf" class="form-check-input"> Second half of the Month
                                        </div>
                                        @if ($halfMonthType)
                                            <div class="ps-5">
                                                <label for="" class="form-label">Select Month for the {{$changeRadioValueToWords}}</label>
                                                <div class="col-8">
                                                    <input type="month" class="form-control" name="datepicker" id="datepicker" wire:model="selectedMonthForMonthType" />
                                                </div>
                                                {{-- {{$selectedMonthForMonthType}} --}}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" wire:model="typetoExport" value="bySite" class="form-check-input"> By site
                        </label>
                    </div>
                    @if ($typetoExport && $typetoExport === $bySite)
                        <div class="ps-4">
                            @if (!$listOfSites)
                                <div class="alert alert-warning" role="alert">
                                    No available Working sites! You must add a working sites before you can continue this action.
                                </div>                                
                            @else    
                                <label for="" class="form-label">Select a site</label>
                                <div class="col-8">
                                    <select class="form-select" wire:model="selectedWorkingSite">
                                        <option value="0">Sites...</option>
                                        @foreach ($listOfSites as $site)
                                            <option value="{{$site->id}}">{{ $site->site_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($selectedWorkingSite)
                                <div class="ps-4 mt-2">
                                    {{$selectedWorkingSite}}
                                    <div class="form-check">
                                        <input type="radio" wire:model="dtrTypeBySite" value="dailyTimeRecord" class="form-check-input"> Daily
                                    </div>
                                    @if ($dtrTypeBySite === 'dailyTimeRecord')
                                        <div class="ps-4">
                                            <label for="" class="form-label">Select a day</label>
                                            <div class="col-8">
                                                <input type="date" class="form-control" name="datepicker" id="datepicker" wire:model="dtrSelectedDateBySite" />
                                            </div>
                                            {{-- {{$dtrSelectedDateBySite}} --}}
                                        </div>
                                    @endif
                                    <div class="form-check">
                                        <input type="radio" wire:model="dtrTypeBySite" value="firstHalf" class="form-check-input"> First half of the Month
                                    </div>
                                    @if ($dtrTypeBySite === 'firstHalf')
                                        <div class="ps-5">
                                            <label for="" class="form-label">Select Month for the {{$changeRadioValueToWords}}</label>
                                            <div class="col-8">
                                                <input type="month" class="form-control" name="datepicker" id="datepicker"
                                                    wire:model="selectedFirstHalfMonthForMonthTypeBySite" />
                                            </div>
                                            {{-- {{$selectedFirstHalfMonthForMonthTypeBySite}} --}}
                                        </div>
                                    @endif
                                    <div class="form-check">
                                        <input type="radio" wire:model="dtrTypeBySite" value="secondHalf" class="form-check-input"> Second half of the Month
                                    </div>
                                    @if ($dtrTypeBySite === 'secondHalf')
                                        <div class="ps-5">
                                            <label for="" class="form-label">Select Month for the {{$changeRadioValueToWords}}</label>
                                            <div class="col-8">
                                                <input type="month" class="form-control" name="datepicker" id="datepicker"
                                                    wire:model="selectedSecondHalfMonthForMonthTypeBySite" />
                                            </div>
                                            {{-- {{$selectedSecondHalfMonthForMonthTypeBySite}} --}}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                    {{-- <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" wire:model="typetoExport" value="individual" class="form-check-input" > Individual
                        </label>
                    </div> --}}

                </div>
                <div class="modal-body">
                    {{-- to export: {{$typetoExport}} --}}
                    @if ($typetoExport && $typetoExport === $allEmployee)
                        {{-- {{$employeeCount}} --}}
                        @if (($halfMonthType && $selectedMonthForMonthType) || ($timeRecordType === $dailyTimeRecord && $selectedDateForDTR))
                            <div class="alert alert-info" role="alert">
                                You are about to export {{$employeeCount}} employees
                            </div>
                        @endif
                    @endif
                    @if ($typetoExport && $typetoExport === $bySite)
                        @if ($dtrTypeBySite && ($dtrSelectedDateBySite || $selectedFirstHalfMonthForMonthTypeBySite || $selectedSecondHalfMonthForMonthTypeBySite))
                            <div class="alert alert-info" role="alert">
                                You are about to export {{$employeeCount}} employees
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    @empty(!$typetoExport)
                        {{-- @if (($halfMonthType && $selectedMonthForMonthType) || ($timeRecordType === $dailyTimeRecord && $selectedDateForDTR)) --}}
                        @if($showExportButton && $employeeCount > 0)    
                            <button type="button" wire:click="exportEmplyeeData()" wire:loading.attr="disabled" class="btn btn-primary">Export</button>
                        @endif
                    @endempty
                    <button type="button" id="closeExportModal" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

