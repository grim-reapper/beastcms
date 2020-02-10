<div class="tab-pane" id="tab_history">
    <div class="form-group" style="min-height: 400px;">
        <table class="table table-bordered table-striped" id="table">
            <thead>
                <tr>
                    <th>{{ trans('Base::tables.author') }}</th>
                    <th>{{ trans('Base::tables.column') }}</th>
                    <th>{{ trans('Base::tables.origin') }}</th>
                    <th>{{ trans('Base::tables.after_change') }}</th>
                    <th>{{ trans('Base::tables.created_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @if ($model->revisionHistory !== null && count($model->revisionHistory)>0)
                    @foreach($model->revisionHistory as $history)
                        <tr>
                            <td style="min-width: 145px;">{{ $history->userResponsible() ? $history->userResponsible()->getFullName() : 'N/A' }}</td>
                            <td style="min-width: 145px;">{{ $history->fieldName() }}</td>
                            <td>{{ $history->oldValue() }}</td>
                            <td><span class="html-diff-content" data-original="{{ $history->oldValue() }}">{{ $history->newValue() }}</span></td>
                            <td style="min-width: 145px;">{{ date_from_database($history->created_at, config('Base.general.date_format.date_time')) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="text-center">
                        <td colspan="5">{{ trans('Base::tables.no_record') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
