@extends('layouts.app')
@section('title',__('navbar.createLink'))

@section('content')
    @parent
    <create-page inline-template
                 map-name="@lang('match/search.map')"
                 search-name="@lang('match/create.create')"
                 init-lng="{{ old('lng') }}"
                 init-lat="{{ old('lat') }}"
                 init-address="{{ old('address') }}"
                 :translate="{
                    'confirmAddress': '@lang('match/create.confirmAddress')'
                 }"
                 v-cloak>
        <div class="container-fluid sm-full-height"
             :class="{ 'sm-no-side-padding' : mapToggled}">
            <flipper :flipped="mapToggled">
                <div class="content-static-right" slot="front">
                    @include('match.create.front')
                </div>
                <div class="map-static-left" slot="back">
                    @include('match.create.back')
                </div>
            </flipper>
            <div class="search-toggle text-center">
                <span class="btn-group d-md-none">
                    <button class="btn btn-primary" @click="toggleMap">@{{mapBtn}}</button>
                </span>
            </div>
        </div>
    </create-page>
@endsection