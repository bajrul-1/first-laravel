@extends('layouts.owner')

@section('content')
    <livewire:owner.employee-onboard-form :company_slug="$company_slug" />
@endsection