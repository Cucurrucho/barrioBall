@extends('layouts.plain')
@section('title','Errors Table')

@section('content')
    @include('partials.navbar.authorized')

    <errors-page inline-template
                 delete-url="/admin/errors"
                 :translate="{
                    error: '@lang('Admin/errors.error')',
                    user: '@lang('Admin/errors.user')',
                    date: '@lang('Admin/errors.date')',
                    resolve: '@lang('Admin/errors.resolve')'
                 }">
        <div class="container-fluid mb-5 mt-5">
            <div class="row">
                <div class="col-12">
                    @component('partials.components.panel')
                        @slot('title')
                            <h4>@lang('Admin/errors.phpErrors')</h4>
                        @endslot
                        <datatable
                                url="{{ action('Admin\ErrorController@getPhpErrors')}}"
                                :fields="phpErrorFields"
                                detail-row="php-detail-row"
                                ref="phpTable"
                                delete-class="btn-success"
                                delete-icon="fa-check"
                                @delete="onDelete"
                                class="mt-3">
                        </datatable>
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @component('partials.components.panel')
                        @slot('title')
                            <h4>@lang('Admin/errors.jsErrors')</h4>
                        @endslot
                        <datatable
                                url="{{ action('Admin\ErrorController@getJsErrors') }}"
                                :fields="jsErrorFields"
                                detail-row="js-detail-row"
                                ref="jsTable"
                                delete-class="btn-success"
                                delete-icon="fa-check"
                            @delete="onDelete">
                        </datatable>
                    @endcomponent
                </div>
            </div>
        </div>
    </errors-page>
@stop
